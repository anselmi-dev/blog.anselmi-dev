import * as THREE from 'three';
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';

/**
 * Frascos 3D para la rueda de /servicios/herramientas.
 *
 * Cada frasco se arma por código (vidrio, contenido, etiqueta y tapa metálica)
 * y la etiqueta es una textura de canvas con la categoría, el nombre y el logo
 * de la herramienta. La rueda (scroll, arrastre, snap) la maneja tools-wheel.js;
 * este módulo solo dibuja lo que le indica getState().
 */

const JAR_WIDTH = 2; // radio 1
const SPACING = JAR_WIDTH * 1.55;
const RADIUS = 16;
const STEP = SPACING / RADIUS;
const LABEL_HEIGHT = 1.05;
const FONT = 'Fraunces, ui-serif, Georgia, serif';

function supportsWebGL() {
    try {
        const canvas = document.createElement('canvas');

        return Boolean(window.WebGLRenderingContext && (canvas.getContext('webgl2') || canvas.getContext('webgl')));
    } catch {
        return false;
    }
}

function lerp(a, b, t) {
    return a + (b - a) * t;
}

function easeInOut(t) {
    return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
}

/** Perfil del vidrio (radio, altura) para LatheGeometry. */
function glassProfile() {
    return [
        [0, 0],
        [0.86, 0],
        [0.95, 0.03],
        [1, 0.14],
        [1, 1.86],
        [0.97, 1.98],
        [0.9, 2.04],
        [0.88, 2.1],
        [0.88, 2.2],
    ].map(([x, y]) => new THREE.Vector2(x, y));
}

/** Perfil de la tapa con borde redondeado. */
function lidProfile() {
    return [
        [0, 2.62],
        [0.86, 2.62],
        [0.93, 2.6],
        [0.965, 2.55],
        [0.97, 2.5],
        [0.97, 2.18],
        [0.95, 2.14],
        [0.9, 2.13],
    ].map(([x, y]) => new THREE.Vector2(x, y));
}

/** Rayas verticales finas para el lateral de la tapa (mapa de rugosidad). */
function lidRidgesTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 512;
    canvas.height = 8;
    const ctx = canvas.getContext('2d');

    for (let x = 0; x < canvas.width; x += 4) {
        ctx.fillStyle = x % 8 === 0 ? '#5a5a5a' : '#2a2a2a';
        ctx.fillRect(x, 0, 4, canvas.height);
    }

    const texture = new THREE.CanvasTexture(canvas);
    texture.wrapS = THREE.RepeatWrapping;
    texture.repeat.set(4, 1);

    return texture;
}

function seededRandom(seed) {
    let value = seed * 9301 + 49297;

    return () => {
        value = (value * 9301 + 49297) % 233280;

        return value / 233280;
    };
}

async function loadLogo(icon, hex) {
    if (! icon) {
        return null;
    }

    try {
        // fetch + blob: la imagen queda como mismo origen y no "ensucia" el canvas.
        const response = await fetch(`https://cdn.simpleicons.org/${encodeURIComponent(icon)}/${hex}`);

        if (! response.ok) {
            return null;
        }

        const svg = await response.text();
        const url = URL.createObjectURL(new Blob([svg], { type: 'image/svg+xml' }));
        const image = new Image();

        await new Promise((resolve, reject) => {
            image.onload = resolve;
            image.onerror = reject;
            image.src = url;
        });

        return image;
    } catch {
        return null;
    }
}

/**
 * Dibuja la etiqueta. La textura envuelve todo el frasco: el centro (u = 0.5)
 * queda de frente a la cámara y los bordes atrás.
 */
function drawLabel(ctx, jar, index, logo) {
    const { width, height } = ctx.canvas;
    const [labelColor, blobColor, inkColor] = jar.palette;
    const random = seededRandom(index + 7);
    const center = width / 2;

    ctx.clearRect(0, 0, width, height);
    ctx.fillStyle = labelColor;
    ctx.fillRect(0, 0, width, height);

    // Manchas tipo camuflaje.
    ctx.fillStyle = blobColor;
    for (let i = 0; i < 9; i++) {
        const x = random() * width;
        const y = random() * height;
        const rx = 90 + random() * 180;
        const ry = 70 + random() * 120;
        ctx.beginPath();
        ctx.ellipse(x, y, rx, ry, random() * Math.PI, 0, Math.PI * 2);
        ctx.fill();
    }

    // Filetes arriba y abajo.
    ctx.fillStyle = inkColor;
    ctx.globalAlpha = 0.35;
    ctx.fillRect(0, 10, width, 3);
    ctx.fillRect(0, height - 13, width, 3);
    ctx.globalAlpha = 1;

    const left = center - 300;
    const maxWidth = 600;

    ctx.fillStyle = inkColor;
    ctx.textBaseline = 'alphabetic';
    ctx.font = `500 30px ${FONT}`;
    ctx.letterSpacing = '8px';
    ctx.globalAlpha = 0.8;
    ctx.fillText(String(jar.category || '').toUpperCase(), left, 90);
    ctx.globalAlpha = 1;
    ctx.letterSpacing = '0px';

    let size = 128;
    ctx.font = `italic 500 ${size}px ${FONT}`;
    while (ctx.measureText(jar.name).width > maxWidth && size > 56) {
        size -= 4;
        ctx.font = `italic 500 ${size}px ${FONT}`;
    }
    ctx.fillText(jar.name, left, 90 + 40 + size * 0.85);

    ctx.font = `400 26px ${FONT}`;
    ctx.globalAlpha = 0.7;
    ctx.fillText('anselmi.dev', left, height - 44);
    ctx.globalAlpha = 1;

    if (logo) {
        ctx.drawImage(logo, center + 300 - 84, 44, 84, 84);
    }

    // Texto chico en la parte de atrás, como la etiqueta trasera de un frasco.
    ctx.font = `400 22px ${FONT}`;
    ctx.globalAlpha = 0.65;
    const lines = [jar.name, jar.category, jar.tagline || '', 'anselmi.dev'];
    lines.forEach((line, i) => {
        ctx.fillText(String(line), 60, 70 + i * 36, 420);
        ctx.fillText(String(line), width - 480, 70 + i * 36, 420);
    });
    ctx.globalAlpha = 1;
}

function createJar(jar, index, materials, onLabelUpdate) {
    const [, , inkColor, fillColor] = jar.palette;
    const group = new THREE.Group();

    const glass = new THREE.Mesh(new THREE.LatheGeometry(glassProfile(), 72), materials.glass);
    glass.renderOrder = 2;
    group.add(glass);

    const fill = new THREE.Mesh(
        new THREE.CylinderGeometry(0.94, 0.9, 1.78, 64),
        new THREE.MeshStandardMaterial({ color: fillColor, roughness: 0.6, metalness: 0 }),
    );
    fill.position.y = 0.06 + 1.78 / 2;
    group.add(fill);

    const canvas = document.createElement('canvas');
    canvas.width = 2048;
    canvas.height = 340;
    const ctx = canvas.getContext('2d');
    const texture = new THREE.CanvasTexture(canvas);
    texture.colorSpace = THREE.SRGBColorSpace;
    texture.anisotropy = 4;

    const redraw = (logo = null) => {
        drawLabel(ctx, jar, index, logo);
        texture.needsUpdate = true;
        onLabelUpdate();
    };

    redraw();
    loadLogo(jar.icon, String(inkColor).replace('#', '')).then((logo) => {
        if (logo) {
            redraw(logo);
        }
    });

    const label = new THREE.Mesh(
        new THREE.CylinderGeometry(1.012, 1.012, LABEL_HEIGHT, 96, 1, true, Math.PI, Math.PI * 2),
        new THREE.MeshStandardMaterial({ map: texture, roughness: 0.5, metalness: 0 }),
    );
    label.position.y = 0.98;
    group.add(label);

    const lid = new THREE.Mesh(new THREE.LatheGeometry(lidProfile(), 96), materials.lid);
    group.add(lid);

    const lidTop = new THREE.Mesh(new THREE.CircleGeometry(0.86, 64), materials.lidTop);
    lidTop.rotation.x = -Math.PI / 2;
    lidTop.position.y = 2.632;
    group.add(lidTop);

    // El origen del frasco queda en su centro.
    group.children.forEach((child) => {
        child.position.y -= 1.31;
        child.userData.jarIndex = index;
    });

    return { group, texture };
}

/**
 * @param {HTMLElement} container
 * @param {Array<{name: string, category: string, tagline: string, url: string, icon: string|null, palette: string[]}>} jars
 * @param {{ getState: () => {current: number, parallax: number}, reducedMotion: boolean }} options
 */
export function createJarScene(container, jars, { getState, reducedMotion }) {
    if (! supportsWebGL()) {
        return null;
    }

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'high-performance' });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    // Neutral conserva mejor los colores de las etiquetas que ACES.
    renderer.toneMapping = THREE.NeutralToneMapping;
    renderer.toneMappingExposure = 1;
    renderer.domElement.className = 'block size-full';
    container.appendChild(renderer.domElement);

    const scene = new THREE.Scene();
    const pmrem = new THREE.PMREMGenerator(renderer);
    const environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
    scene.environment = environment;

    const keyLight = new THREE.DirectionalLight(0xffffff, 1.6);
    keyLight.position.set(4, 6, 8);
    scene.add(keyLight);
    scene.add(new THREE.AmbientLight(0xffffff, 0.35));

    const camera = new THREE.PerspectiveCamera(26, 1, 0.1, 200);

    const ridges = lidRidgesTexture();
    const materials = {
        // Vidrio translúcido con reflejos (sin "transmission": sobre fondo
        // transparente se ve lechoso y además es lo más costoso de renderizar).
        glass: new THREE.MeshPhysicalMaterial({
            color: 0xffffff,
            metalness: 0,
            roughness: 0.05,
            transparent: true,
            opacity: 0.22,
            depthWrite: false,
            envMapIntensity: 1.6,
            clearcoat: 1,
            clearcoatRoughness: 0.04,
            specularIntensity: 1,
        }),
        lid: new THREE.MeshStandardMaterial({ color: 0xeceef1, metalness: 1, roughness: 0.28, roughnessMap: ridges }),
        lidTop: new THREE.MeshStandardMaterial({ color: 0xf1f2f4, metalness: 1, roughness: 0.2 }),
    };

    let needsRender = true;
    const wheel = new THREE.Group();
    scene.add(wheel);

    const items = jars.map((jar, index) => {
        const { group, texture } = createJar(jar, index, materials, () => {
            needsRender = true;
        });
        const pivot = new THREE.Group();
        pivot.add(group);
        wheel.add(pivot);

        return { pivot, spin: group, texture };
    });

    const raycaster = new THREE.Raycaster();
    const pointer = new THREE.Vector2();
    let viewport = { halfWidth: 1, halfHeight: 1, narrow: false };
    let frame = null;
    let visible = true;
    let lastTime = performance.now();
    let time = 0;

    // Modo detalle: un frasco viaja al costado, crece y gira sobre su eje.
    const focus = { index: -1, amount: 0, from: 0, to: 0, startedAt: 0, duration: 900, spin: 0 };

    const resize = () => {
        const width = container.clientWidth || 1;
        const height = container.clientHeight || 1;
        const narrow = width < 640;
        const jarWidthPx = Math.min(190, Math.max(130, width * 0.32));
        const pixelsPerUnit = jarWidthPx / JAR_WIDTH;
        const halfHeight = height / 2 / pixelsPerUnit;
        const distance = halfHeight / Math.tan(THREE.MathUtils.degToRad(camera.fov / 2));

        camera.aspect = width / height;
        camera.position.set(0, 0, distance);
        camera.lookAt(0, 0, 0);
        camera.updateProjectionMatrix();
        renderer.setSize(width, height, false);

        viewport = { halfWidth: halfHeight * camera.aspect, halfHeight, narrow };
        // El frasco activo queda un poco por encima del centro, como en la versión CSS.
        wheel.position.y = halfHeight * 0.16;
        needsRender = true;
    };

    const update = (delta) => {
        const { current, parallax } = getState();

        if (! reducedMotion) {
            time += delta;
        }

        if (focus.startedAt) {
            const t = Math.min(1, (performance.now() - focus.startedAt) / focus.duration);
            focus.amount = lerp(focus.from, focus.to, easeInOut(t));

            if (t >= 1) {
                focus.startedAt = 0;

                if (focus.to === 0) {
                    focus.index = -1;
                }
            }
        }

        if (focus.index >= 0 && ! reducedMotion) {
            focus.spin += delta * 0.6;
        }

        const focusX = viewport.narrow ? 0 : -viewport.halfWidth * 0.32;
        const focusY = viewport.narrow ? viewport.halfHeight * 0.32 - wheel.position.y : -wheel.position.y * 0.6;
        const focusScale = viewport.narrow ? 1.15 : 1.6;

        wheel.position.x = parallax / 60;

        items.forEach((item, i) => {
            const offset = i - current;
            const angle = offset * STEP;
            const distance = Math.abs(offset);

            let x = RADIUS * Math.sin(angle);
            let y = RADIUS * Math.cos(angle) - RADIUS;
            let rotationZ = -angle;
            let scale = 1 - Math.min(distance, 3) * 0.09;
            let spin = reducedMotion ? 0 : offset * 0.9 + Math.sin(time * 0.8 + i) * 0.12;

            if (focus.index >= 0) {
                const amount = focus.amount;

                if (i === focus.index) {
                    x = lerp(x, focusX, amount);
                    y = lerp(y, focusY, amount);
                    rotationZ = lerp(rotationZ, 0, amount);
                    scale = lerp(scale, focusScale, amount);
                    // Una vuelta completa al entrar y luego giro lento sobre su eje.
                    spin = lerp(spin, Math.PI * 2 * amount + focus.spin, amount);
                } else {
                    y -= amount * viewport.halfHeight * 1.6;
                    scale *= 1 - amount * 0.4;
                }
            }

            item.pivot.position.set(x, y, 0);
            item.pivot.rotation.z = rotationZ;
            item.pivot.scale.setScalar(Math.max(0.0001, scale));
            item.pivot.visible = distance < 3.6 || i === focus.index;
            item.spin.rotation.y = spin;
            item.spin.rotation.x = reducedMotion ? 0 : Math.sin(time * 0.6 + i * 1.7) * 0.03;
        });
    };

    const loop = (now) => {
        frame = null;
        const delta = Math.min(0.05, (now - lastTime) / 1000);
        lastTime = now;

        if (! visible) {
            return;
        }

        update(delta);
        renderer.render(scene, camera);
        needsRender = false;

        if (! reducedMotion || focus.startedAt || needsRender) {
            frame = requestAnimationFrame(loop);
        }
    };

    const start = () => {
        if (frame === null) {
            lastTime = performance.now();
            frame = requestAnimationFrame(loop);
        }
    };

    const observer = new IntersectionObserver(([entry]) => {
        visible = entry.isIntersecting;

        if (visible) {
            start();
        }
    });
    observer.observe(container);

    resize();
    update(0);
    renderer.render(scene, camera);
    start();

    return {
        resize,
        /** Pide un frame (necesario con reduced motion, donde no hay loop continuo). */
        invalidate() {
            needsRender = true;
            start();
        },
        /** Índice del frasco bajo el puntero, o -1. */
        pick(clientX, clientY) {
            const rect = renderer.domElement.getBoundingClientRect();
            camera.updateMatrixWorld();
            pointer.set(((clientX - rect.left) / rect.width) * 2 - 1, -((clientY - rect.top) / rect.height) * 2 + 1);
            raycaster.setFromCamera(pointer, camera);
            // Las matrices pueden estar desactualizadas si el loop está en pausa.
            scene.updateMatrixWorld();
            camera.updateMatrixWorld();
            const hit = raycaster.intersectObjects(items.map((item) => item.pivot), true)[0];

            return hit ? hit.object.userData.jarIndex ?? -1 : -1;
        },
        focus(index) {
            focus.index = index;
            focus.spin = 0;
            focus.from = focus.amount;
            focus.to = 1;
            focus.duration = reducedMotion ? 1 : 1100;
            focus.startedAt = performance.now();
            start();
        },
        blur() {
            if (focus.index < 0) {
                return;
            }

            focus.from = focus.amount;
            focus.to = 0;
            focus.duration = reducedMotion ? 1 : 700;
            focus.startedAt = performance.now();
            start();
        },
        destroy() {
            if (frame !== null) {
                cancelAnimationFrame(frame);
            }

            observer.disconnect();
            scene.traverse((object) => {
                object.geometry?.dispose();

                if (object.material && ! Object.values(materials).includes(object.material)) {
                    object.material.dispose();
                }
            });
            items.forEach((item) => item.texture.dispose());
            Object.values(materials).forEach((material) => material.dispose());
            ridges.dispose();
            environment.dispose();
            pmrem.dispose();
            renderer.dispose();
            renderer.domElement.remove();
        },
    };
}
