/**
 * Desplazamiento de un frasco respecto a la posición actual, dando la vuelta
 * (bucle infinito). Por defecto deja ~30 % de los frascos atrás y el resto
 * adelante; `lead` fija cuántos quedan atrás.
 */
export function wrappedOffset(index, position, count, lead = count * 0.3) {
    return ((((index - position + lead) % count) + count) % count) - lead;
}
