/*
| Category types
*/
export type Category = {
    name: string;
};

export type CategoryContextType = {
    category: string;
    setCategory: (category: string) => void;
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
| Product Attributes types
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
Cart Types
 */
export type CartItem = {
    product: Product;
    selectedAttributes: Record<string, string>;
    quantity: number;
};

export type CartContextType = {
    cartItems: CartItem[];
    addToCart: (item: CartItem) => void;
    increaseQty: (item: CartItem) => void;
    decreaseQty: (item: CartItem) => void;
    total: number;
    isCartOpen: boolean;
    openCart: () => void;
    closeCart: () => void;
    clearCart: () => void;
};

/*
| Order Input Types
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