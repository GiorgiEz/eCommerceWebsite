import {createContext, type ReactNode, useContext, useEffect, useState} from "react";
import type { CartItem, CartContextType } from "../utils/types";


// React Context to provide global access to cart state and actions
const CartContext = createContext<CartContextType | null>(null);

// Provider component that manages cart state and exposes it to the entire app
export function CartProvider({ children }: { children: ReactNode }) {
    // Initialize cart state from localStorage (with fallback and error handling)
    const [cartItems, setCartItems] = useState<CartItem[]>(() => {
        try {
            const stored = localStorage.getItem("cart");
            return stored ? JSON.parse(stored) : [];
        } catch {
            localStorage.removeItem("cart");
            return [];
        }
    });
    const [isCartOpen, setIsCartOpen] = useState(false);

    const openCart = () => setIsCartOpen(true);
    const closeCart = () => setIsCartOpen(false);
    const clearCart = () => {setCartItems([]); localStorage.removeItem("cart")};

    // Persist cart state to localStorage whenever cartItems change
    useEffect(() => {
        localStorage.setItem("cart", JSON.stringify(cartItems));
    }, [cartItems]);

    // Determines if two cart items are identical based on product ID and selected attributes
    // NOTE: JSON.stringify is used for deep comparison of selected attributes
    const isSameItem = (a: CartItem, b: CartItem) => {
        return (
            a.product.external_id === b.product.external_id &&
            JSON.stringify(a.selectedAttributes) === JSON.stringify(b.selectedAttributes)
        );
    };

    // Adds item to cart or increases quantity if identical item already exists
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

    // Increases quantity of a matching cart item
    const increaseQty = (item: CartItem) => {
        setCartItems((prev) =>
            prev.map((p) =>
                isSameItem(p, item)
                    ? { ...p, quantity: p.quantity + 1 }
                    : p
            )
        );
    };

    // Decreases quantity of a matching cart item and removes it if quantity reaches zero
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

    // Calculates total cart price based on item price and quantity
    const total = cartItems.reduce(
        (sum, item) => sum + item.quantity * item.product.prices[0].amount,
        0
    );

    return (
        <CartContext.Provider
            value={{
                cartItems,
                addToCart, openCart, closeCart, clearCart,
                increaseQty, decreaseQty,
                total,
                isCartOpen,
            }}
        >
            {children}
        </CartContext.Provider>
    );
}

// Custom hook to safely access cart context within components
export function useCart() {
    const context = useContext(CartContext);

    if (!context) {
        throw new Error("useCart must be used inside provider");
    }

    return context;
}