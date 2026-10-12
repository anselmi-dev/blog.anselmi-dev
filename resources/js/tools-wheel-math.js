/**
 * Desplazamiento de un frasco respecto a la posición actual, dando la vuelta
 * (bucle infinito). Deja ~30 % de los frascos atrás y el resto adelante.
 */
export function wrappedOffset(index, position, count) {
    const lead = count * 0.3;

    return ((((index - position + lead) % count) + count) % count) - lead;
}
