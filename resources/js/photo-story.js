/**
 * Formato historia de Instagram (1080 × 1920) para compartir una foto de la
 * galería: la foto de fondo y, centrada encima, una ficha redondeada con la
 * foto, el título, el lugar, la exposición en badges y la paleta de colores
 * de la propia foto en puntos. Todo en <canvas>, sin librerías.
 */

import { drawPhoto, fontFamily, loadImage, roundedRect, wrapLines } from './photo-share';

const WIDTH = 1080;
const HEIGHT = 1920;

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
    const cardHeight = inset + heroHeight + 30 + infoHeight;
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

    // Lugar, cámara y fecha; abajo la exposición en badges y los colores en puntos.
    const infoX = heroX + 16;
    const infoWidth = heroWidth - 32;
    const infoY = heroY + heroHeight + 30;

    context.fillStyle = '#3f342e';
    const place = data.place || data.title || '';
    fitFont(context, place, 700, family, 40, infoWidth, 28);
    context.fillText(wrapLines(context, place, infoWidth, 1)[0] || '', infoX, infoY + 44);

    context.font = `500 24px ${family}`;
    context.fillStyle = '#6b5e57';
    const details = [data.camera, [data.date, data.credit].filter(Boolean).join(' · ')].filter(Boolean);
    let detailY = infoY + 86;

    for (const line of details) {
        context.fillText(wrapLines(context, line, infoWidth, 1)[0], infoX, detailY);
        detailY += 34;
    }

    const rowY = infoY + 156;
    const badgeHeight = 44;

    // Colores: puntos chicos a la derecha.
    const dotRadius = 13;
    const dotGap = 10;
    const dotsWidth = palette.length * dotRadius * 2 + Math.max(0, palette.length - 1) * dotGap;
    const dotsStart = infoX + infoWidth - dotsWidth;

    palette.forEach((color, index) => {
        context.beginPath();
        context.arc(dotsStart + dotRadius + index * (dotRadius * 2 + dotGap), rowY + badgeHeight / 2, dotRadius, 0, Math.PI * 2);
        context.fillStyle = `rgb(${color.join(',')})`;
        context.fill();
        context.lineWidth = 1.5;
        context.strokeStyle = 'rgba(43, 43, 43, 0.18)';
        context.stroke();
    });

    // Exposición: badges minimalistas a la izquierda, sin pisar los puntos.
    context.font = `600 21px ${family}`;
    context.textBaseline = 'middle';
    let badgeX = infoX;

    for (const value of (data.exposure || []).filter(Boolean)) {
        const width = context.measureText(value).width + 32;

        if (badgeX + width > dotsStart - 20) {
            break;
        }

        context.lineWidth = 1.5;
        context.strokeStyle = 'rgba(43, 43, 43, 0.2)';
        roundedRect(context, badgeX, rowY, width, badgeHeight, badgeHeight / 2);
        context.stroke();

        context.fillStyle = '#3f342e';
        context.fillText(value, badgeX + 16, rowY + badgeHeight / 2 + 1);
        badgeX += width + 10;
    }

    context.textBaseline = 'alphabetic';

    return new Promise((resolve, reject) => {
        canvas.toBlob((blob) => (blob ? resolve(blob) : reject(new Error('No se pudo generar la imagen.'))), 'image/png');
    });
}
