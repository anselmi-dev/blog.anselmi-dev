/**
 * Formato historia de Instagram (1080 × 1920) para compartir una foto de la
 * galería: la foto de fondo y, centrada encima, una ficha redondeada con la
 * foto, el título, la técnica, la fecha y la paleta de colores sacada de la
 * propia foto. Todo en <canvas>, sin librerías.
 */

import { drawPhoto, fontFamily, loadImage, roundedRect, wrapLines } from './photo-share';

const WIDTH = 1080;
const HEIGHT = 1920;

const ICONS = {
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

    const canvas = document.createElement('canvas');
    canvas.width = WIDTH;
    canvas.height = HEIGHT;

    const context = canvas.getContext('2d');

    // Fondo: la foto completa, apenas oscurecida para que resalte la ficha.
    drawPhoto(context, bitmap, 0, 0, WIDTH, HEIGHT, 0, 0.5);
    context.fillStyle = 'rgba(0, 0, 0, 0.18)';
    context.fillRect(0, 0, WIDTH, HEIGHT);

    // Ficha: centrada en la imagen, con esquinas redondeadas como en la web.
    const cardX = 92;
    const cardWidth = WIDTH - cardX * 2;
    const inset = 16;
    const heroHeight = 520;
    const infoHeight = 230;
    const paletteHeight = 206;
    const cardHeight = inset + heroHeight + 30 + infoHeight + paletteHeight;
    const cardY = Math.round((HEIGHT - cardHeight) / 2);

    context.save();
    context.shadowColor = 'rgba(0, 0, 0, 0.28)';
    context.shadowBlur = 60;
    context.shadowOffsetY = 24;
    context.fillStyle = '#ffffff';
    roundedRect(context, cardX, cardY, cardWidth, cardHeight, 40);
    context.fill();
    context.restore();

    const heroX = cardX + inset;
    const heroY = cardY + inset;
    const heroWidth = cardWidth - inset * 2;

    drawPhoto(context, bitmap, heroX, heroY, heroWidth, heroHeight, 26, 0.62);
    bitmap.close?.();

    const shade = context.createLinearGradient(heroX, 0, heroX + heroWidth, 0);
    shade.addColorStop(0, 'rgba(0, 0, 0, 0.62)');
    shade.addColorStop(0.85, 'rgba(0, 0, 0, 0.15)');
    context.save();
    roundedRect(context, heroX, heroY, heroWidth, heroHeight, 26);
    context.clip();
    context.fillStyle = shade;
    context.fillRect(heroX, heroY, heroWidth, heroHeight);
    context.restore();

    const textX = heroX + 56;
    const textWidth = heroWidth - 140;

    context.textBaseline = 'alphabetic';
    context.fillStyle = '#ffffff';
    context.font = `600 22px ${family}`;
    const kickerY = heroY + 150;
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

    // Técnica, lugar y fecha.
    const bottomY = heroY + heroHeight + 30;

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
    roundedRect(context, boxX, boxY, 130, 160, 18);
    context.stroke();
    context.fillStyle = '#2b2b2b';
    context.font = `700 64px ${family}`;
    context.textAlign = 'center';
    context.fillText(data.day || '', boxX + 65, boxY + 92);
    context.font = `600 18px ${family}`;
    context.fillText((data.month || '').toUpperCase(), boxX + 65, boxY + 130);
    context.textAlign = 'left';

    // Colores de la foto, dentro de la ficha.
    const paletteY = bottomY + infoHeight;

    context.strokeStyle = 'rgba(43, 43, 43, 0.12)';
    context.lineWidth = 2;
    context.beginPath();
    context.moveTo(heroX + 8, paletteY);
    context.lineTo(heroX + heroWidth - 8, paletteY);
    context.stroke();

    context.fillStyle = '#2b2b2b';
    context.font = `700 22px ${family}`;
    spacedText(context, (data.labels?.color || 'COLOR').toUpperCase(), heroX + 8, paletteY + 52, 3);

    context.textAlign = 'center';

    palette.forEach((color, index) => {
        const centerX = heroX + 8 + (index + 0.5) * ((heroWidth - 16) / palette.length);

        context.beginPath();
        context.arc(centerX, paletteY + 110, 30, 0, Math.PI * 2);
        context.fillStyle = `rgb(${color.join(',')})`;
        context.fill();
        context.lineWidth = 2;
        context.strokeStyle = 'rgba(43, 43, 43, 0.15)';
        context.stroke();

        context.fillStyle = '#5c4f48';
        context.font = `600 18px ${family}`;
        context.fillText(toHex(color), centerX, paletteY + 170);
    });

    context.textAlign = 'left';

    return new Promise((resolve, reject) => {
        canvas.toBlob((blob) => (blob ? resolve(blob) : reject(new Error('No se pudo generar la imagen.'))), 'image/png');
    });
}
