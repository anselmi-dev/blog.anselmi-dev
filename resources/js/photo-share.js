/**
 * "Compartir" y "Descargar" en la ficha de una foto de la galería: arma una
 * imagen vertical (1080 × 1350, formato de Instagram) con la foto y sus datos
 * en un <canvas>, sin librerías, y la comparte con el menú del sistema o la
 * descarga como PNG.
 *
 * Dos formatos: "post" (1080 × 1350) y "story" (historia, 1080 × 1920,
 * ver photo-story.js).
 *
 * Uso (Alpine): x-data="photoShare(@js($datos), @js($mensajes))", con
 *   { image, title, date, location, camera, technique: [], url, site, label, story: {...} }
 */

import { renderStoryCard } from './photo-story';

const WIDTH = 1080;
const HEIGHT = 1350;
const PADDING = 64;

const COLORS = {
    background: '#f2efe5',
    ink: '#18181b',
    muted: '#71717a',
    chip: '#ffffff',
    line: 'rgba(24, 24, 27, 0.12)',
};

export function fontFamily() {
    return getComputedStyle(document.body).fontFamily || 'system-ui, sans-serif';
}

/**
 * Las fotos se sirven desde /storage del mismo sitio: se piden por el origen
 * actual para que el canvas no quede "contaminado" si el enlace usa otro host
 * (con o sin www).
 */
function sameOriginUrl(src) {
    const url = new URL(src, window.location.href);

    if (url.origin !== window.location.origin && url.pathname.startsWith('/storage/')) {
        return `${window.location.origin}${url.pathname}${url.search}`;
    }

    return url.href;
}

export async function loadImage(src) {
    const response = await fetch(sameOriginUrl(src), { credentials: 'same-origin' });

    if (! response.ok) {
        throw new Error(`No se pudo cargar la foto (${response.status}).`);
    }

    return createImageBitmap(await response.blob());
}

export function roundedRect(context, x, y, width, height, radius) {
    context.beginPath();
    context.roundRect(x, y, width, height, radius);
}

export function wrapLines(context, text, maxWidth, maxLines) {
    const words = String(text).split(/\s+/).filter(Boolean);
    const lines = [];
    let current = '';

    for (const word of words) {
        const candidate = current ? `${current} ${word}` : word;

        if (context.measureText(candidate).width <= maxWidth || ! current) {
            current = candidate;
        } else {
            lines.push(current);
            current = word;
        }
    }

    if (current) {
        lines.push(current);
    }

    if (lines.length > maxLines) {
        const kept = lines.slice(0, maxLines);
        let last = kept[maxLines - 1];

        while (last.length > 1 && context.measureText(`${last}…`).width > maxWidth) {
            last = last.slice(0, -1).trimEnd();
        }

        kept[maxLines - 1] = `${last}…`;

        return kept;
    }

    return lines;
}

/**
 * Dibuja la foto recortada (cover) dentro de un rectángulo redondeado.
 */
export function drawPhoto(context, bitmap, x, y, width, height, radius = 36, focusY = 0.5) {
    const scale = Math.max(width / bitmap.width, height / bitmap.height);
    const sourceWidth = width / scale;
    const sourceHeight = height / scale;
    const sourceX = (bitmap.width - sourceWidth) / 2;
    const sourceY = (bitmap.height - sourceHeight) * focusY;

    context.save();
    roundedRect(context, x, y, width, height, radius);
    context.clip();
    context.drawImage(bitmap, sourceX, sourceY, sourceWidth, sourceHeight, x, y, width, height);
    context.restore();
}

function drawChips(context, chips, x, y, maxWidth, family) {
    context.font = `600 28px ${family}`;
    context.textBaseline = 'middle';

    const height = 56;
    let cursorX = x;
    let cursorY = y;

    for (const chip of chips) {
        const width = context.measureText(chip).width + 44;

        if (cursorX + width > x + maxWidth && cursorX > x) {
            cursorX = x;
            cursorY += height + 14;
        }

        context.fillStyle = COLORS.chip;
        roundedRect(context, cursorX, cursorY, width, height, height / 2);
        context.fill();

        context.fillStyle = COLORS.ink;
        context.fillText(chip, cursorX + 22, cursorY + height / 2 + 1);

        cursorX += width + 12;
    }

    return chips.length ? cursorY + height : y;
}

export async function renderPhotoCard(data) {
    const family = fontFamily();

    await Promise.allSettled([
        document.fonts?.load(`800 60px ${family}`),
        document.fonts?.load(`600 28px ${family}`),
        document.fonts?.load(`500 30px ${family}`),
    ]);

    const bitmap = await loadImage(data.image);

    const canvas = document.createElement('canvas');
    canvas.width = WIDTH;
    canvas.height = HEIGHT;

    const context = canvas.getContext('2d');
    const contentWidth = WIDTH - PADDING * 2;

    context.fillStyle = COLORS.background;
    context.fillRect(0, 0, WIDTH, HEIGHT);

    // Foto: ocupa lo que dejan libre los textos, entre 520 y 700 px de alto.
    const photoHeight = Math.round(Math.min(700, Math.max(520, contentWidth * (bitmap.height / bitmap.width))));
    drawPhoto(context, bitmap, PADDING, PADDING, contentWidth, photoHeight);
    bitmap.close?.();

    let y = PADDING + photoHeight + 56;
    context.textBaseline = 'alphabetic';

    if (data.date) {
        context.font = `600 26px ${family}`;
        context.fillStyle = COLORS.muted;
        context.fillText(String(data.date).toUpperCase(), PADDING, y);
        y += 62;
    }

    context.font = `800 60px ${family}`;
    context.fillStyle = COLORS.ink;

    for (const line of wrapLines(context, data.title, contentWidth, 2)) {
        context.fillText(line, PADDING, y);
        y += 70;
    }

    const details = [data.location, data.camera].filter(Boolean);

    if (details.length) {
        context.font = `500 30px ${family}`;
        context.fillStyle = COLORS.muted;
        y += 2;

        for (const detail of details) {
            context.fillText(wrapLines(context, detail, contentWidth, 1)[0], PADDING, y);
            y += 44;
        }
    }

    const technique = (data.technique || []).filter(Boolean);

    if (technique.length) {
        drawChips(context, technique, PADDING, y + 4, contentWidth, family);
    }

    // Pie: sitio a la izquierda, sección a la derecha.
    const footerY = HEIGHT - PADDING;

    context.strokeStyle = COLORS.line;
    context.lineWidth = 2;
    context.beginPath();
    context.moveTo(PADDING, footerY - 56);
    context.lineTo(WIDTH - PADDING, footerY - 56);
    context.stroke();

    context.textBaseline = 'alphabetic';
    context.font = `700 30px ${family}`;
    context.fillStyle = COLORS.ink;
    context.fillText(data.site || window.location.host, PADDING, footerY);

    if (data.label) {
        context.font = `500 28px ${family}`;
        context.fillStyle = COLORS.muted;
        context.textAlign = 'right';
        context.fillText(data.label, WIDTH - PADDING, footerY);
        context.textAlign = 'left';
    }

    return new Promise((resolve, reject) => {
        canvas.toBlob((blob) => (blob ? resolve(blob) : reject(new Error('No se pudo generar la imagen.'))), 'image/png');
    });
}

function fileName(title, suffix = '') {
    const slug = String(title || 'foto')
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '');

    return `${slug || 'foto'}${suffix}.png`;
}

function download(blob, name) {
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = name;
    document.body.append(link);
    link.click();
    link.remove();
    setTimeout(() => URL.revokeObjectURL(url), 1000);
}

export function initPhotoShare() {
    document.addEventListener('alpine:init', () => {
        window.Alpine.data('photoShare', (data, messages = {}) => ({
            busy: false,
            canShare: false,
            format: 'story',

            init() {
                try {
                    const probe = new File([''], 'probe.png', { type: 'image/png' });
                    this.canShare = typeof navigator.share === 'function' && navigator.canShare?.({ files: [probe] }) === true;
                } catch (_) {
                    this.canShare = false;
                }
            },

            async build() {
                const isStory = this.format === 'story';
                const blob = isStory
                    ? await renderStoryCard({ image: data.image, title: data.title, ...data.story })
                    : await renderPhotoCard(data);

                return new File([blob], fileName(data.title, isStory ? '-historia' : ''), { type: 'image/png' });
            },

            async share() {
                if (this.busy) {
                    return;
                }

                this.busy = true;

                try {
                    const file = await this.build();

                    if (this.canShare && navigator.canShare({ files: [file] })) {
                        await navigator.share({ files: [file], title: data.title, text: data.url });
                    } else {
                        download(file, file.name);
                    }
                } catch (error) {
                    if (error?.name !== 'AbortError') {
                        this.fail();
                    }
                } finally {
                    this.busy = false;
                }
            },

            async save() {
                if (this.busy) {
                    return;
                }

                this.busy = true;

                try {
                    const file = await this.build();
                    download(file, file.name);
                    window.notify?.({ variant: 'success', title: messages.saved, duration: 3000 });
                } catch (_) {
                    this.fail();
                } finally {
                    this.busy = false;
                }
            },

            fail() {
                window.notify?.({ variant: 'error', title: messages.failed, message: messages.failedHint });
            },
        }));
    });
}
