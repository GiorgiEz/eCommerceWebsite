/*
| Category
*/

export type Category = {
    name: string;
};


/*
| Currency
*/

export type Currency = {
    label: string;
    symbol: string;
};


/*
| Price
*/

export type Price = {
    amount: number;
    currency: Currency;
};


/*
| Product Attributes
*/

export type AttributeItem = {
    external_id: string;
    value: string;
    displayValue: string;
};

export type Attribute = {
    external_id: string;
    name: string;
    type: string;
    items: AttributeItem[];
};


/*
| Product
*/

export type Product = {
    external_id: string;
    name: string;
    brand: string;
    description: string;
    gallery: string[];
    attributes: Attribute[];
    prices: Price[];
    inStock: boolean;
    thumbnail: string;
};


/*
Cart Item
 */
export type CartItem = {
    product: Product;
    selectedAttributes: Record<string, string>;
    quantity: number;
};


/*
| Order Input
*/

export type OrderAttributeInput = {
    attributeId: string;
    attributeItemId: string;
};

export type OrderItemInput = {
    productId: string;
    quantity: number;
    price: number;
    attributes: OrderAttributeInput[];
};

export type CreateOrderInput = {
    orderDate: string;
    orderTotalAmount: number;
    items: OrderItemInput[];
};