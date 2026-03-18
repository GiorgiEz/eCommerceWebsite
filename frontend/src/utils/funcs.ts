import type { Price } from "./types.ts";


/* Convert string to kebab-case */
export function toKebabCase(str: string): string {
    return str.
    toLowerCase()
        .replace(/\s+/g, "-")
        .replace(/[^\w-]/g, "");
}

export function truncateText(html: string, limit: number): string {
    const text = html.replace(/<[^>]+>/g, ""); // strip HTML tags
    return text.length > limit ? text.slice(0, limit) + "..." : text;
}

export function getFormattedPrice(prices: Price[]): string {
    if (!prices || prices.length === 0) return "";

    // Try to find USD ($)
    const usdPrice = prices.find(
        (p) => p.currency.symbol === "$"
    );

    const price = usdPrice ?? prices[0];

    return `${price.currency.symbol}${price.amount}`;
}