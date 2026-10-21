/**
 * Arranca la demo de ascii-sharks en cada canvas [data-ascii-ocean].
 * El motor vive en app/CuriousCode/ascii-sharks y se carga solo en esta página.
 * Al salir (wire:navigate) hay que destruirlo: registra listeners en window.
 */

const oceans = new Set();

function releaseDetachedOceans() {
    for (const entry of oceans) {
        if (entry.canvas.isConnected) {
            continue;
        }

        entry.cancelled = true;
        entry.engine?.destroy();
        oceans.delete(entry);
    }
}

export function initAsciiOceans(root = document) {
    releaseDetachedOceans();

    root.querySelectorAll('[data-ascii-ocean]').forEach((canvas) => {
        if (canvas.dataset.asciiOceanBound === 'true') {
            return;
        }

        canvas.dataset.asciiOceanBound = 'true';

        const entry = { canvas, engine: null, cancelled: false };
        oceans.add(entry);

        import('../../app/CuriousCode/ascii-sharks/src/index.ts')
            .then(({ OceanEngine }) => {
                if (entry.cancelled || ! canvas.isConnected) {
                    entry.engine?.destroy();
                    oceans.delete(entry);

                    return;
                }

                entry.engine = new OceanEngine(canvas);
            })
            .catch(() => {
                oceans.delete(entry);

                if (canvas.isConnected) {
                    delete canvas.dataset.asciiOceanBound;
                }
            });
    });
}
