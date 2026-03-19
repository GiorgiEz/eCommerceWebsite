import {createContext, type ReactNode, useContext, useEffect, useState} from "react";
import type { CartItem, CartContextType } from "../utils/types";


const CartContext = createContext<CartContextType | null>(null);

export function CartProvider({ children }: { children: ReactNode }) {
    const [cartItems, setCartItems] = useState<CartItem[]>(() => {
        try {
            const stored = localStorage.getItem("cart");
            return stored ? JSON.parse(stored) : [];
        } catch {
            localStorage.removeItem("cart");
            return [];
        }
    });
    const [isOpen, setIsOpen] = useState(false);

    const openCart = () => setIsOpen(true);
    const closeCart = () => setIsOpen(false);
    const clearCart = () => {
        setCartItems([]);
        localStorage.removeItem("cart");
    };

    useEffect(() => {
        localStorage.setItem("cart", JSON.stringify(cartItems));
    }, [cartItems]);

    const isSameItem = (a: CartItem, b: CartItem) => {
        return (
            a.product.external_id === b.product.external_id &&
            JSON.stringify(a.selectedAttributes) ===
            JSON.stringify(b.selectedAttributes)
        );
    };

    const addToCart = (item: CartItem) => {
        setCartItems((prev) => {
            const existing = prev.find((p) => isSameItem(p, item));

            if (existing) {
                return prev.map((p) =>
                    isSameItem(p, item)
                        ? { ...p, quantity: p.quantity + 1 }
                        : p
                );
            }

            return [...prev, item];
        });
    };

    const increaseQty = (item: CartItem) => {
        setCartItems((prev) =>
            prev.map((p) =>
                isSameItem(p, item)
                    ? { ...p, quantity: p.quantity + 1 }
                    : p
            )
        );
    };

    const decreaseQty = (item: CartItem) => {
        setCartItems((prev) =>
            prev
                .map((p) =>
                    isSameItem(p, item)
                        ? { ...p, quantity: p.quantity - 1 }
                        : p
                )
                .filter((p) => p.quantity > 0)
        );
    };

    const total = cartItems.reduce(
        (sum, item) => sum + item.quantity * item.product.prices[0].amount,
        0
    );

    return (
        <CartContext.Provider
            value={{
                cartItems,
                addToCart,
                increaseQty,
                decreaseQty,
                total,
                isOpen,
                openCart,
                closeCart,
                clearCart,
            }}
        >
            {children}
        </CartContext.Provider>
    );
}

export function useCart() {
    const ctx = useContext(CartContext);
    if (!ctx) throw new Error("useCart must be used inside provider");
    return ctx;
}