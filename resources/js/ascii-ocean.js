/**
 * Arranca la demo de ascii-sharks en cada canvas [data-ascii-ocean].
 * El motor vive en app/CuriousCode/ascii-sharks y se carga solo en esta página.
 * Al salir (wire:navigate) hay que destruirlo: registra listeners en window.
 *
 * ?sharks=mako,tiger elige hasta cuatro especies. La primera es la más grande.
 * Sin parámetro, el agua muestra cuatro tiburones clásicos.
 */

const SHARK_KINDS = ['classic', 'hammerhead', 'zebra', 'tiger', 'greathammer', 'mako', 'thresher', 'nurse'];

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

function readKinds() {
    const value = new URLSearchParams(window.location.search).get('sharks');

    if (value === null || value === '') {
        return ['classic'];
    }

    const kinds = value
        .split(',')
        .map((kind) => kind.trim())
        .filter((kind) => SHARK_KINDS.includes(kind));

    return (kinds.length ? kinds : ['classic']).slice(0, 4);
}

function isDefaultRoster(kinds) {
    return kinds.length === 1 && kinds[0] === 'classic';
}

function specsFor(kinds) {
    if (isDefaultRoster(kinds)) {
        return undefined;
    }

    const roster = kinds.length === 1 ? [kinds[0], kinds[0], kinds[0], kinds[0]] : kinds;

    return roster.map((kind, index) => ({
        kind,
        size: Math.max(0.55, 1 - index * 0.12),
    }));
}

function writeKinds(kinds) {
    const url = new URL(window.location.href);

    if (isDefaultRoster(kinds)) {
        url.searchParams.delete('sharks');
    } else {
        url.searchParams.set('sharks', kinds.join(','));
    }

    window.history.replaceState(window.history.state, '', url);
}

function paintKinds(root, kinds) {
    root.querySelectorAll('[data-ascii-ocean-kind]').forEach((button) => {
        button.setAttribute('aria-pressed', kinds.includes(button.dataset.asciiOceanKind) ? 'true' : 'false');
    });
}

function mountOcean(entry) {
    if (! entry.OceanEngine || entry.cancelled || ! entry.canvas.isConnected) {
        return;
    }

    entry.engine?.destroy();
    entry.engine = new entry.OceanEngine(entry.canvas, specsFor(entry.kinds));
}

function bindRoster(entry) {
    const root = entry.root;

    if (! root || root.dataset.asciiOceanRosterBound === 'true') {
        return;
    }

    root.dataset.asciiOceanRosterBound = 'true';
    paintKinds(root, entry.kinds);

    root.querySelectorAll('[data-ascii-ocean-kind]').forEach((button) => {
        button.addEventListener('click', () => {
            const kind = button.dataset.asciiOceanKind;

            if (! kind) {
                return;
            }

            const selected = entry.kinds.includes(kind)
                ? entry.kinds.filter((item) => item !== kind)
                : [...entry.kinds, kind];

            entry.kinds = (selected.length ? selected : ['classic']).slice(0, 4);
            writeKinds(entry.kinds);
            paintKinds(root, entry.kinds);
            mountOcean(entry);
        });
    });
}

export function initAsciiOceans(root = document) {
    releaseDetachedOceans();

    root.querySelectorAll('[data-ascii-ocean]').forEach((canvas) => {
        if (canvas.dataset.asciiOceanBound === 'true') {
            return;
        }

        canvas.dataset.asciiOceanBound = 'true';

        const entry = {
            canvas,
            root: canvas.closest('[data-ascii-ocean-root]'),
            engine: null,
            OceanEngine: null,
            kinds: readKinds(),
            cancelled: false,
        };

        oceans.add(entry);
        bindRoster(entry);

        import('../../app/CuriousCode/ascii-sharks/src/index.ts')
            .then(({ OceanEngine }) => {
                entry.OceanEngine = OceanEngine;
                mountOcean(entry);

                if (entry.cancelled || ! entry.canvas.isConnected) {
                    entry.engine?.destroy();
                    oceans.delete(entry);
                }
            })
            .catch(() => {
                oceans.delete(entry);

                if (canvas.isConnected) {
                    delete canvas.dataset.asciiOceanBound;
                }
            });
    });
}
