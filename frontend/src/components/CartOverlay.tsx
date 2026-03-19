import { useState } from "react";
import { useCart } from "../context/CartContext";
import { useCreateOrder } from "../hooks/useCreateOrder.ts"
import { toKebabCase, getFormattedPrice } from "../utils/funcs.ts"
import type { CartItem } from "../utils/types";


export default function CartOverlay() {
    const { isOpen, openCart, closeCart, clearCart } = useCart();
    const { cartItems, increaseQty, decreaseQty, total } = useCart();
    const { createOrder, loading } = useCreateOrder();
    const [orderSuccess, setOrderSuccess] = useState(false);

    const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
    const isPlaceOrderDisabled = cartItems.length === 0 || orderSuccess || loading;

    const handlePlaceOrder = async () => {
        try {
            const orderInput = {
                orderDate: new Date().toISOString().slice(0, 19).replace("T", " "),
                orderTotalAmount: total,
                items: cartItems.map(item => ({
                    productId: item.product.external_id,
                    quantity: item.quantity,
                    price: Number(item.product.prices[0].amount),
                    attributes: Object.entries(item.selectedAttributes).map(
                        ([attributeId, attributeItemId]) =>
                            ({attributeId, attributeItemId})
                    )
                }))
            };

            await createOrder(orderInput);
            clearCart();
            closeCart();

            setOrderSuccess(true);  // show message

            setTimeout(() => {
                setOrderSuccess(false);
            }, 2000);  // hide message after 2 seconds

        } catch (error) {
            console.error(error);
        }
    };

    return (
        <div className="relative">
            {/* CART BUTTON */}
            <button data-testid="cart-btn" className="relative h-[4vh] text-[2vw]" onClick={() => openCart()}> 🛒
                {/* Item count bubble */}
                {totalItems > 0 && (
                    <span className="absolute -top-2 -right-2 bg-black text-white text-xs w-5 h-5 flex
                        items-center justify-center rounded-full">
                        {totalItems}
                    </span>
                )}
            </button>

            {/* ORDER SUCCESSFUL MESSAGE */}
            {orderSuccess && (
                <div className="
                        fixed top-20 left-1/2 -translate-x-1/2
                        bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50
                      ">
                    Order placed successfully!
                </div>
            )}

            {/* OVERLAY */}
            {isOpen && (
                <>
                    {/* BACKDROP (MAKE BACKGROUND GRAY WHEN CART IS OPEN) */}
                    <div
                        className="fixed left-0 right-0 bottom-0 top-[8vh] bg-black/30 z-40"
                        onClick={() => closeCart()}
                    />

                    {/* PANEL */}
                    <div className="absolute right-0 mt-4 bg-white shadow-lg z-50 p-4 w-[25vw] max-h-[60vh] text-[1vw]">

                        {/* ITEM COUNT */}
                        <div className="font-semibold mb-2">
                            {totalItems} {totalItems === 1 ? "Item" : "Items"}
                        </div>

                        {/* CART ITEMS */}
                        <div className="h-[40vh] overflow-y-auto space-y-4">
                            {cartItems.map((item: CartItem, index) => (

                                <div key={index} className="flex gap-2 w-full mb-10 items-stretch min-h-[12vh]">

                                    {/* INFO (NAME, PRICE, ATTRIBUTES) */}
                                    <div className="basis-[40%] min-w-0">
                                        <div className="truncate">{item.product.name}</div>
                                        <div>{getFormattedPrice(item.product.prices)}</div>

                                        {/* ATTRIBUTES */}
                                        {item.product.attributes.map((attr) => {
                                            const attrKebab = toKebabCase(attr.name);

                                            return (
                                                <div
                                                    key={attr.external_id}
                                                    data-testid={`cart-item-attribute-${attrKebab}`}
                                                    className="mt-2"
                                                >
                                                    <div>{attr.name}:</div>

                                                    <div className="flex flex-wrap gap-1 mt-1 max-w-full">
                                                        {attr.items.map((opt) => {
                                                            const selected =
                                                                item.selectedAttributes[attr.external_id] ===
                                                                opt.external_id;

                                                            const baseId = `cart-item-attribute-${attrKebab}-${toKebabCase(opt.value)}`;

                                                            return (
                                                                <div
                                                                    key={opt.external_id}
                                                                    data-testid={
                                                                        selected
                                                                            ? `${baseId}-selected`
                                                                            : baseId
                                                                    }
                                                                    className={`border ${
                                                                        selected
                                                                            ? "border-black"
                                                                            : "border-gray-300"
                                                                    } min-w-0`}
                                                                >
                                                                    {attr.type === "swatch" ? (
                                                                        /* COLOR SWATCH */
                                                                        <div
                                                                            className="w-4 h-4 shrink-0"
                                                                            style={{ backgroundColor: opt.value }}
                                                                        />
                                                                    ) : (
                                                                        /* TEXT */
                                                                        <div
                                                                            className={`px-2 py-1 text-[0.8vw] whitespace-nowrap 
                                                                            overflow-hidden text-ellipsis ${
                                                                                selected
                                                                                    ? "bg-black text-white"
                                                                                    : "bg-white"
                                                                            }`}
                                                                        >
                                                                            {opt.value}
                                                                        </div>
                                                                    )}
                                                                </div>
                                                            );
                                                        })}
                                                    </div>
                                                </div>
                                            );
                                        })}
                                    </div>

                                    {/* QUANTITY CONTROLS */}
                                    <div className="basis-[20%] flex flex-col items-center justify-between">
                                        <button
                                            data-testid="cart-item-amount-increase"
                                            onClick={() => increaseQty(item)}
                                            className="border px-2 w-[1.5vw] flex items-center justify-center"
                                        >
                                            +
                                        </button>

                                        <div data-testid="cart-item-amount">{item.quantity}</div>

                                        <button
                                            data-testid="cart-item-amount-decrease"
                                            onClick={() => decreaseQty(item)}
                                            className="border px-2 w-[1.5vw] flex items-center justify-center"
                                        >
                                            -
                                        </button>
                                    </div>

                                    {/* IMAGE */}
                                    <div className="basis-[40%] flex items-center justify-center">
                                        <div className="w-full h-[10vh] flex items-center justify-center">
                                            <img
                                                src={item.product.gallery[0]}
                                                alt={item.product.name}
                                                className="max-h-full max-w-full object-contain"
                                            />
                                        </div>
                                    </div>

                                </div>
                            ))}
                        </div>

                        {/* TOTAL AMOUNT */}
                        <div className="flex justify-between mt-4 mb-2 font-semibold">
                            <span>Total</span>
                            <span data-testid="cart-total">{"$" + total.toFixed(2)}</span>
                        </div>

                        {/* PLACE ORDER BUTTON */}
                        <button
                            disabled={isPlaceOrderDisabled}
                            className={`
                                w-full py-2 font-bold ${isPlaceOrderDisabled ? "" : "hover:bg-green-400"} 
                                ${isPlaceOrderDisabled ? "bg-gray-400" : "bg-green-500 text-white"}
                            `}
                            onClick={handlePlaceOrder}
                        >
                            {loading ? "PLACING..." : orderSuccess ? "ORDER PLACED" : "PLACE ORDER"}
                        </button>
                    </div>
                </>
            )}
        </div>
    );
}