import type { Product } from "../utils/types";
import { useCart } from "../context/CartContext";
import { client } from "../graphql/client";
import { GET_PRODUCT } from "../graphql/queries/product";


export default function QuickShop({ product }: {product: Product}) {
    const { addToCart, openCart } = useCart();

    if (!product.inStock) return null;

    const handleQuickShop = async (e: React.MouseEvent) => {
        e.stopPropagation();

        try {
            // Manual request
            const data = await client.request(GET_PRODUCT, {externalId: product.external_id});
            const fullProduct: Product = data.product;

            // default attributes
            const defaultAttributes = Object.fromEntries(
                fullProduct.attributes.map((attr: any) => [attr.external_id, attr.items[0].external_id])
            );

            addToCart({product: fullProduct, selectedAttributes: defaultAttributes, quantity: 1});
            openCart();

        } catch (error) {
            console.error("QuickShop failed:", error);
        }
    };

    return (
        <button
            className="
                hidden group-hover:flex absolute bottom-4 right-4
                bg-green-400 text-white w-10 h-10 rounded-full
                items-center justify-center shadow-md hover:bg-green-300
            "
            onClick={handleQuickShop}
        >
            🛒
        </button>
    );
}