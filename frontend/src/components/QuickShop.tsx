import type { Product } from "../utils/types";
import { useCart } from "../context/CartContext";
import { client } from "../graphql/client";
import { GET_PRODUCT } from "../graphql/queries/product";


// QuickShop button component for adding a product to cart with default attributes
export default function QuickShop({ product }: {product: Product}) {
    const { addToCart, openCart } = useCart();  // Access cart actions from global context

    // Do not render button if product is out of stock
    if (!product.inStock) return null;

    // Handles quick add-to-cart by fetching full product data and selecting default attributes
    const handleQuickShop = async (e: React.MouseEvent) => {
        e.stopPropagation();  // Prevent click from triggering parent navigation (e.g., product card click)

        try {
            // Fetch full product details (including attributes) from API
            const data = await client.request(GET_PRODUCT, {externalId: product.external_id});
            const fullProduct: Product = data.product;  // Extract full product object from response

            // Generate default attribute selection (first option for each attribute)
            const defaultAttributes = Object.fromEntries(
                fullProduct.attributes.map((attr: any) => [attr.external_id, attr.items[0].external_id])
            );

            // Add product with default attributes to cart and open cart overlay
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