import type { Price } from "./types.ts";


/* Convert string to kebab-case */
export function toKebabCase(str: string): string {
    return str.
    toLowerCase()
        .replace(/\s+/g, "-")
        .replace(/[^\w-]/g, "");
}

/* Removes HTML tags and truncates description text based on limit */
export function truncateText(html: string, limit: number): string {
    const text = html.replace(/<[^>]+>/g, "");

    if (text.length <= limit) return text;

    const truncated = text.slice(0, limit);
    const lastSpace = truncated.lastIndexOf(" ");

    return truncated.slice(0, lastSpace) + "...";
}

/* Extracts $ price from all prices, if $ doesn't exist gets first price */
export function getFormattedPrice(prices: Price[]): string {
    if (!prices || prices.length === 0) return "";

    // Try to find USD ($)
    const usdPrice = prices.find(
        (p) => p.currency.symbol === "$"
    );

    const price = usdPrice ?? prices[0];

    return `${price.currency.symbol}${price.amount}`;
}