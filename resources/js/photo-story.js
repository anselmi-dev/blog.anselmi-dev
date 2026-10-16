/**
 * Formato historia de Instagram (1080 × 1920) para compartir una foto de la
 * galería: la foto de fondo, la paleta de colores sacada de la propia foto,
 * la tipografía del sitio y abajo una "maqueta" de página web con la foto,
 * el título, la técnica y la fecha. Todo en <canvas>, sin librerías.
 */

import { drawPhoto, fontFamily, loadImage, roundedRect, wrapLines } from './photo-share';

const WIDTH = 1080;
const HEIGHT = 1920;

const ICONS = {
    heart: ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z'],
    send: ['M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z', 'm21.854 2.147-10.94 10.939'],
    bookmark: ['m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z'],
    aperture: ['M2 12a10 10 0 1 0 20 0 10 10 0 1 0-20 0', 'm14.31 8 5.74 9.94', 'M9.69 8h11.48', 'm7.38 12 5.74-9.94', 'M9.69 16 3.95 6.06', 'M14.31 16H2.83', 'm16.62 12-5.74 9.94'],
};

function drawIcon(context, name, x, y, size, color, lineWidth = 2) {
    context.save();
    context.translate(x, y);
    context.scale(size / 24, size / 24);
    context.strokeStyle = color;
    context.lineWidth = lineWidth;
    context.lineCap = 'round';
    context.lineJoin = 'round';

    for (const path of ICONS[name]) {
        context.stroke(new Path2D(path));
    }

    context.restore();
}

/**
 * Texto con letras separadas (como "H O M E  P A G E").
 */
function spacedText(context, text, x, y, spacing, align = 'left') {
    const letters = [...text];
    const width = letters.reduce((total, letter) => total + context.measureText(letter).width, 0) + spacing * (letters.length - 1);
    let cursor = align === 'center' ? x - width / 2 : align === 'right' ? x - width : x;

    for (const letter of letters) {
        context.fillText(letter, cursor, y);
        cursor += context.measureText(letter).width + spacing;
    }
}

function toHex([red, green, blue]) {
    return `#${[red, green, blue].map((value) => value.toString(16).padStart(2, '0')).join('').toUpperCase()}`;
}

function distance(a, b) {
    return Math.hypot(a[0] - b[0], a[1] - b[1], a[2] - b[2]);
}

function saturation([red, green, blue]) {
    const max = Math.max(red, green, blue);

    return max === 0 ? 0 : (max - Math.min(red, green, blue)) / max;
}

/**
 * Los colores más presentes de la foto: la achica a 64 × 64, agrupa colores
 * parecidos y se queda con los más frecuentes que no se repitan entre sí.
 *
 * @return {number[][]} hasta `count` colores [r, g, b]
 */
export function extractPalette(bitmap, count = 4) {
    const size = 64;
    const canvas = document.createElement('canvas');
    canvas.width = size;
    canvas.height = size;

    const context = canvas.getContext('2d', { willReadFrequently: true });
    context.drawImage(bitmap, 0, 0, size, size);

    const { data } = context.getImageData(0, 0, size, size);
    const buckets = new Map();

    for (let index = 0; index < data.length; index += 4) {
        const key = ((data[index] >> 4) << 8) | ((data[index + 1] >> 4) << 4) | (data[index + 2] >> 4);
        const bucket = buckets.get(key) ?? { count: 0, red: 0, green: 0, blue: 0 };

        bucket.count += 1;
        bucket.red += data[index];
        bucket.green += data[index + 1];
        bucket.blue += data[index + 2];
        buckets.set(key, bucket);
    }

    const ranked = [...buckets.values()]
        .sort((a, b) => b.count - a.count)
        .map((bucket) => [bucket.red, bucket.green, bucket.blue].map((sum) => Math.round(sum / bucket.count)));

    const palette = [];

    for (const color of ranked) {
        if (palette.every((picked) => distance(picked, color) > 70)) {
            palette.push(color);
        }

        if (palette.length === count) {
            break;
        }
    }

    return palette;
}

/**
 * El color más vivo de la paleta para el botón; si la foto es muy apagada,
 * un naranja como el de la referencia.
 */
function accentFrom(palette) {
    const vivid = [...palette].sort((a, b) => saturation(b) - saturation(a))[0];

    return vivid && saturation(vivid) > 0.35 ? vivid : [245, 140, 30];
}

function readableInk([red, green, blue]) {
    return (red * 299 + green * 587 + blue * 114) / 1000 > 150 ? '#1a1a1a' : '#ffffff';
}

function fitFont(context, text, weight, family, size, maxWidth, minSize) {
    let current = size;
    context.font = `${weight} ${current}px ${family}`;

    while (current > minSize && context.measureText(text).width > maxWidth) {
        current -= 4;
        context.font = `${weight} ${current}px ${family}`;
    }

    return current;
}

export async function renderStoryCard(data) {
    const family = fontFamily();

    await Promise.allSettled([
        document.fonts?.load(`800 96px ${family}`),
        document.fonts?.load(`700 40px ${family}`),
        document.fonts?.load(`500 24px ${family}`),
    ]);

    const bitmap = await loadImage(data.image);
    const palette = extractPalette(bitmap);
    const accent = accentFrom(palette);

    const canvas = document.createElement('canvas');
    canvas.width = WIDTH;
    canvas.height = HEIGHT;

    const context = canvas.getContext('2d');

    // Fondo: la foto completa, con un velo arriba para que se lea el texto.
    drawPhoto(context, bitmap, 0, 0, WIDTH, HEIGHT, 0, 0.5);

    const veil = context.createLinearGradient(0, 0, 0, HEIGHT * 0.62);
    veil.addColorStop(0, 'rgba(0, 0, 0, 0.5)');
    veil.addColorStop(1, 'rgba(0, 0, 0, 0)');
    context.fillStyle = veil;
    context.fillRect(0, 0, WIDTH, HEIGHT);

    context.shadowColor = 'rgba(0, 0, 0, 0.25)';
    context.shadowBlur = 12;
    context.fillStyle = '#ffffff';
    context.textBaseline = 'alphabetic';

    // Paleta
    const columnX = 440;
    context.font = `700 26px ${family}`;
    spacedText(context, (data.labels?.color || 'COLOR').toUpperCase(), columnX, 300, 3);

    palette.forEach((color, index) => {
        const x = columnX + 30 + index * 82;

        context.beginPath();
        context.arc(x, 370, 30, 0, Math.PI * 2);
        context.fillStyle = `rgb(${color.join(',')})`;
        context.fill();
        context.lineWidth = 3;
        context.strokeStyle = 'rgba(255, 255, 255, 0.85)';
        context.stroke();
    });

    context.fillStyle = 'rgba(255, 255, 255, 0.9)';
    context.font = `500 17px ${family}`;
    context.fillText(palette.map(toHex).join('  '), columnX, 440);

    // Tipografía
    context.fillStyle = '#ffffff';
    context.font = `700 26px ${family}`;
    spacedText(context, (data.labels?.typography || 'TIPOGRAFÍA').toUpperCase(), columnX, 540, 3);
    context.font = `700 44px ${family}`;
    context.fillText(family.split(',')[0].replaceAll(/["']/g, '').trim(), columnX, 600);
    context.font = `500 24px ${family}`;
    context.fillStyle = 'rgba(255, 255, 255, 0.85)';
    context.fillText(data.labels?.typographyNote || '', columnX, 636);

    // "G A L E R Í A ."
    context.fillStyle = '#ffffff';
    context.font = `600 30px ${family}`;
    spacedText(context, `${(data.labels?.page || 'GALERÍA').toUpperCase()}.`, WIDTH / 2, 810, 26, 'center');
    context.shadowColor = 'transparent';

    // Maqueta de página: marco blanco que sigue hasta abajo.
    const frameX = 92;
    const frameY = 900;
    const frameWidth = WIDTH - frameX * 2;

    context.fillStyle = '#ffffff';
    context.fillRect(frameX, frameY, frameWidth, HEIGHT - frameY);

    const heroX = frameX + 16;
    const heroY = frameY + 16;
    const heroWidth = frameWidth - 32;
    const heroHeight = 520;

    drawPhoto(context, bitmap, heroX, heroY, heroWidth, heroHeight, 0, 0.62);
    bitmap.close?.();

    const shade = context.createLinearGradient(heroX, 0, heroX + heroWidth, 0);
    shade.addColorStop(0, 'rgba(0, 0, 0, 0.62)');
    shade.addColorStop(0.85, 'rgba(0, 0, 0, 0.15)');
    context.fillStyle = shade;
    context.fillRect(heroX, heroY, heroWidth, heroHeight);

    const textX = heroX + 56;
    const textWidth = heroWidth - 140;

    context.fillStyle = '#ffffff';
    context.font = `600 22px ${family}`;
    const kickerY = heroY + 130;
    context.fillText(wrapLines(context, (data.kicker || '').toUpperCase(), textWidth, 1)[0] || '', textX, kickerY);

    const title = String(data.title || '').toLowerCase();
    const titleSize = fitFont(context, title, 800, family, 96, textWidth, 56);
    const titleLines = wrapLines(context, title, textWidth, 2);
    let y = kickerY + 14 + titleSize * 0.85;

    for (const line of titleLines) {
        context.fillText(line, textX - 3, y);
        y += titleSize * 0.92;
    }

    if (data.description) {
        context.font = `500 22px ${family}`;
        context.fillStyle = 'rgba(255, 255, 255, 0.92)';
        y += 6;

        for (const line of wrapLines(context, data.description, textWidth - 40, 3)) {
            context.fillText(line, textX, y);
            y += 30;
        }
    }

    const buttonLabel = data.labels?.button || 'ver galería';
    context.font = `700 24px ${family}`;
    const buttonWidth = context.measureText(buttonLabel).width + 40;
    const buttonY = Math.min(y + 24, heroY + heroHeight - 76);

    context.fillStyle = `rgb(${accent.join(',')})`;
    roundedRect(context, textX, buttonY, buttonWidth, 46, 10);
    context.fill();
    context.fillStyle = readableInk(accent);
    context.textBaseline = 'middle';
    context.fillText(buttonLabel, textX + 20, buttonY + 24);

    // Barra de navegación
    const navY = heroY + heroHeight + 46;
    const ink = '#1f1a17';

    context.strokeStyle = ink;
    context.lineWidth = 2;
    context.beginPath();
    context.arc(heroX + 12, navY, 10, 0, Math.PI * 2);
    context.stroke();

    context.fillStyle = ink;
    context.font = `600 19px ${family}`;
    const credit = data.credit || '';
    context.fillText(credit, heroX + 32, navY);

    // Los enlaces van centrados entre el crédito y los íconos; si no entran, achica la letra.
    const iconsX = heroX + heroWidth - 140;
    const linksStart = heroX + 32 + context.measureText(credit).width + 32;
    const linksSpace = iconsX - 28 - linksStart;
    const links = data.labels?.nav || ['INICIO', 'SOBRE MÍ', 'GALERÍA', 'CONTACTO'];
    let linkSize = 21;
    let linksWidth;

    do {
        context.font = `500 ${linkSize}px ${family}`;
        linksWidth = links.reduce((total, link) => total + context.measureText(link).width, 0) + 26 * (links.length - 1);
        linkSize -= 1;
    } while (linksWidth > linksSpace && linkSize > 13);

    let linkX = linksStart + Math.max(0, (linksSpace - linksWidth) / 2);

    for (const link of links) {
        context.fillText(link, linkX, navY);
        linkX += context.measureText(link).width + 26;
    }

    ['heart', 'send', 'bookmark'].forEach((icon, index) => {
        drawIcon(context, icon, iconsX + index * 50, navY - 14, 28, ink);
    });

    // Bloque inferior: técnica, texto y fecha.
    const bottomY = navY + 60;
    context.textBaseline = 'alphabetic';

    drawIcon(context, 'aperture', heroX + 40, bottomY, 64, '#f2c418', 2.4);

    context.fillStyle = '#4a3b33';
    context.font = `700 54px ${family}`;
    context.fillText(data.aperture || data.focal || '', heroX + 8, bottomY + 140);
    context.font = `500 38px ${family}`;
    context.fillText(data.iso || '', heroX + 8, bottomY + 186);

    const middleX = heroX + 260;
    const middleWidth = 360;

    context.fillStyle = '#4a3b33';
    const sectionTitle = data.place || data.title || '';
    fitFont(context, sectionTitle, 700, family, 42, middleWidth, 28);
    context.fillText(wrapLines(context, sectionTitle, middleWidth, 1)[0] || '', middleX, bottomY + 82);

    context.font = `500 24px ${family}`;
    context.fillStyle = '#5c4f48';
    let detailY = bottomY + 124;

    for (const line of [data.camera, data.technique].filter(Boolean)) {
        context.fillText(wrapLines(context, line, middleWidth, 1)[0], middleX, detailY);
        detailY += 34;
    }

    // Fecha en un cuadro, como la etiqueta de la referencia.
    const boxX = heroX + heroWidth - 160;
    const boxY = bottomY + 40;

    context.strokeStyle = '#2b2b2b';
    context.lineWidth = 2;
    context.beginPath();
    context.moveTo(boxX - 120, bottomY + 6);
    context.lineTo(boxX - 40, bottomY + 6);
    context.stroke();

    context.fillStyle = '#2b2b2b';
    context.font = `600 18px ${family}`;
    context.fillText(data.labels?.original || 'foto original', boxX - 30, bottomY + 2);
    context.fillText(data.credit || '', boxX - 30, bottomY + 26);

    context.lineWidth = 3;
    context.strokeStyle = '#5b6d7a';
    context.strokeRect(boxX, boxY, 130, 160);
    context.fillStyle = '#2b2b2b';
    context.font = `700 64px ${family}`;
    context.textAlign = 'center';
    context.fillText(data.day || '', boxX + 65, boxY + 92);
    context.font = `600 18px ${family}`;
    context.fillText((data.month || '').toUpperCase(), boxX + 65, boxY + 130);
    context.textAlign = 'left';

    // Pie: etiquetas a la izquierda, agradecimiento espaciado a la derecha.
    const footerY = HEIGHT - 26;

    context.strokeStyle = '#2b2b2b';
    context.lineWidth = 2;
    context.beginPath();
    context.moveTo(heroX + 8, footerY - 40);
    context.lineTo(heroX + 200, footerY - 40);
    context.stroke();

    context.font = `500 18px ${family}`;
    const thanks = `${(data.labels?.thanks || 'GRACIAS POR VER').toUpperCase()} ©`;
    const thanksWidth = [...thanks].reduce((total, letter) => total + context.measureText(letter).width, 0) + 12 * (thanks.length - 1);

    context.fillStyle = '#2b2b2b';
    spacedText(context, thanks, heroX + heroWidth, footerY, 12, 'right');

    context.font = `500 22px ${family}`;
    const tags = (data.tags || []).slice(0, 3).map((tag) => tag.toUpperCase()).join('    ');
    context.fillText(wrapLines(context, tags, heroWidth - thanksWidth - 48, 1)[0] || '', heroX + 8, footerY);

    return new Promise((resolve, reject) => {
        canvas.toBlob((blob) => (blob ? resolve(blob) : reject(new Error('No se pudo generar la imagen.'))), 'image/png');
    });
}
