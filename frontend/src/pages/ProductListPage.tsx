import QuickShop from "../components/QuickShop.tsx"
import { useProducts } from "../hooks/useProducts";
import { useCategory } from "../context/CategoryContext";
import { useNavigate } from "react-router-dom";
import type { Product } from "../utils/types.ts";
import { toKebabCase, getFormattedPrice } from "../utils/funcs.ts"


export default function ProductListPage() {
    const { category } = useCategory();
    const { data, loading } = useProducts(category);

    const products: Product[] = data?.products ?? [];

    const navigate = useNavigate();

    if (loading) {
        return <div className="p-6">Loading products...</div>;
    }

    return (
        <div className="max-w-7xl mx-auto p-6 pt-[10vh]">
            {/* Category Title */}
            <h1 className="text-3xl font-semibold mb-8 capitalize">{category}</h1>

            {/* Product Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {products.map((product) => {
                    return (
                        <div
                            key={product.external_id}
                            data-testid={`product-${toKebabCase(product.name)}`}
                            className="group relative cursor-pointer hover:shadow-lg transition"
                            onClick={() => navigate(`/product/${product.external_id}`)}
                        >
                            {/* Product Image */}
                            <div className="relative p-6 text-[clamp(12px,1.2vw,20px)]">
                                <img
                                    src={product.thumbnail} alt={product.name}
                                    className={`w-full h-[30vh] object-contain ${!product.inStock ? "opacity-40" : ""}`}
                                />

                                {/* Out of stock overlay */}
                                {!product.inStock && (
                                    <div className="absolute inset-0 flex items-center justify-center text-gray-400 font-medium">
                                        OUT OF STOCK
                                    </div>
                                )}

                                {/* Quick shop button */}
                                <QuickShop product={product} />

                                {/* Product Name */}
                                <div
                                    className={`mt-4 font-medium ${
                                        !product.inStock ? "text-gray-400" : "text-black"
                                    }`}
                                >
                                    {product.name}
                                </div>

                                {/* Product Price */}
                                <div
                                    className={`mt-1 font-semibold ${
                                        !product.inStock ? "text-gray-400" : "text-gray-700"
                                    }`}
                                >
                                    {getFormattedPrice(product.prices)}
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}