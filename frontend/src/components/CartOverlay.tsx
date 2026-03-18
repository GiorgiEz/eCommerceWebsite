import { useCart } from "../context/CartContext";
import { useCreateOrder } from "../hooks/useCreateOrder.ts"
import { toKebabCase, getFormattedPrice } from "../utils/funcs.ts"
import type { CartItem } from "../utils/types";


export default function CartOverlay() {
    const { isOpen, openCart, closeCart, clearCart } = useCart();
    const { cartItems, increaseQty, decreaseQty, total } = useCart();
    const {createOrder} = useCreateOrder();

    const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);

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
                        ([attributeId, attributeItemId]) => ({
                            attributeId,
                            attributeItemId
                        })
                    )
                }))
            };

            await createOrder(orderInput);
            clearCart();

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

            {/* OVERLAY */}
            {isOpen && (
                <>
                    {/* BACKDROP */}
                    <div className="fixed left-0 right-0 bottom-0 top-[8vh] bg-black/30 z-40" onClick={() => closeCart()}/>
                    {/* PANEL */}
                    <div className="absolute right-0 mt-4 bg-white shadow-lg z-50 p-4 w-[25vw] max-h-[60vh] text-[1vw]">
                        {/* Header */}
                        <div className="font-semibold mb-2">{totalItems} {totalItems === 1 ? "Item" : "Items"}</div>
                        {/* Cart items */}
                        <div className=" h-[40vh] overflow-y-auto space-y-4">
                            {cartItems.map((item: CartItem, index) => (
                                <div key={index} className="flex gap-4 mb-10 pt-2">

                                    {/* Info */}
                                    <div className="flex-1">
                                        <div>{item.product.name}</div>
                                        <div>{getFormattedPrice(item.product.prices)}</div>

                                        {/* Attributes */}
                                        {item.product.attributes.map((attr) => {
                                            const attrKebab = toKebabCase(attr.name);

                                            return (
                                                <div
                                                    key={attr.external_id}
                                                    data-testid={`cart-item-attribute-${attrKebab}`}
                                                    className="mt-2"
                                                >
                                                    <div className="">{attr.name}:</div>

                                                    <div className="flex gap-1 mt-1">
                                                        {attr.items.map((opt) => {
                                                            const selected =
                                                                item.selectedAttributes[attr.external_id] ===
                                                                opt.external_id;

                                                            const baseId = `cart-item-attribute-${attrKebab}-${toKebabCase(opt.value)}`;

                                                            return (
                                                                <div
                                                                    key={opt.external_id}
                                                                    data-testid={
                                                                        selected ? `${baseId}-selected` : baseId
                                                                    }
                                                                    className={`border ${
                                                                        selected ? "border-black" : "border-gray-300"
                                                                    }`}
                                                                >
                                                                    {attr.type === "swatch" ? (
                                                                        /* COLOR SWATCH */
                                                                        <div
                                                                            className="w-6 h-6"
                                                                            style={{ backgroundColor: opt.value }}
                                                                        />
                                                                    ) : (
                                                                        /* TEXT (size, etc.) */
                                                                        <div className={`px-2 py-1 ${
                                                                            selected ? "bg-black text-white" : "bg-white"
                                                                        }`}>
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

                                    {/* Quantity controls */}
                                    <div className="flex flex-col items-center justify-between">
                                        <button
                                            data-testid="cart-item-amount-increase"
                                            onClick={() => increaseQty(item)}
                                            className="border px-2"
                                        >
                                            +
                                        </button>

                                        <div data-testid="cart-item-amount">{item.quantity}</div>

                                        <button
                                            data-testid="cart-item-amount-decrease"
                                            onClick={() => decreaseQty(item)}
                                            className="border px-2"
                                        >
                                            -
                                        </button>
                                    </div>

                                    {/* Image */}
                                    <img
                                        src={item.product.gallery[0]}
                                        alt={item.product.name}
                                        className="w-36 h-36 object-contain"
                                    />
                                </div>
                            ))}

                        </div>

                        {/* Total */}
                        <div className="flex justify-between mt-4 mb-2 font-semibold">
                            <span>Total</span>
                            <span data-testid="cart-total">{'$' + total.toFixed(2)}</span>
                        </div>

                        {/* Place Order */}
                        <button
                            disabled={cartItems.length === 0}
                            className={`w-full py-2 font-bold hover:bg-green-400 ${
                                cartItems.length === 0 ? "bg-gray-400" : "bg-green-500 text-white"}`}
                            onClick={handlePlaceOrder}
                        >
                            PLACE ORDER
                        </button>
                    </div>
                </>
            )}
        </div>
    );
}