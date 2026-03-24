import { useParams } from "react-router-dom";
import { useState } from "react";
import parse from "html-react-parser";
import { useProduct } from "../hooks/useProduct";
import { useCart } from "../context/CartContext";
import type { Product, AttributeItem } from "../utils/types.ts";
import { toKebabCase, truncateText, getFormattedPrice } from "../utils/funcs.ts"


// Product details page that displays full product info, handles attribute selection, and allows adding to cart
export default function ProductDetailsPage() {
    // Extract product ID from URL and fetch corresponding product data
    const { externalId } = useParams();
    const { data, loading } = useProduct(externalId as string);

    const { addToCart, openCart } = useCart();  // Access cart actions from global context

    // Local state for selected attributes, active image index, and description toggle
    const [selectedAttributes, setSelectedAttributes] = useState<Record<string, string>>({});
    const [activeImage, setActiveImage] = useState(0);
    const [descriptionExpanded, setDescriptionExpanded] = useState(false);

    if (loading) {
        return <div className="p-8">Loading product...</div>;
    }

    const product: Product | undefined = data?.product;

    if (!product) {
        return <div className="p-8">Product not found</div>;
    }

    // Determine if description exceeds truncation limit (strip HTML before measuring)
    const isLong = product.description.replace(/<[^>]+>/g, "").length > 250;

    // Update selected attributes when user selects an option
    const selectAttribute = (attributeId: string, itemId: string) => {
        setSelectedAttributes((prev) => ({
            ...prev,
            [attributeId]: itemId
        }));
    };

    // Check if all required attributes have been selected
    const allAttributesSelected = product.attributes.length === Object.keys(selectedAttributes).length;

    return (
        <div className="max-w-15/16 mx-auto grid grid-cols-[100px_1fr_400px] gap-8 p-[5vw] pt-[16vh]">

            {/* LEFT: Product image thumbnails */}
            <div className="flex flex-col gap-3 overflow-y-auto max-h-[60vh] w-[8vw]" data-testid="product-gallery">
                {product.gallery.map((img, index) => (
                    <img
                        key={index}
                        src={img}
                        alt="thumbnail"
                        className={`cursor-pointer border max-w-[10vw] max-h-[20vh] ${
                            activeImage === index ? "border-black" : "border-transparent"
                        }`}
                        onClick={() => setActiveImage(index)}
                    />
                ))}
            </div>

            {/* CENTER: Main product image with navigation arrows */}
            <div className="relative h-[60vh] w-full max-w-[50vw] mx-auto">
                {/* Image */}
                <img
                    src={product.gallery[activeImage]}
                    alt={product.name}
                    className="w-full h-full min-w-[30vw] object-contain"
                />

                {/* Navigate to previous image */}
                {activeImage > 0 && (
                    <button
                        className="
                            absolute left-2 top-1/2 -translate-y-1/2
                            bg-black/70 text-white px-3 py-2
                            hover:bg-black
                          "
                        onClick={() => setActiveImage((prev) => prev - 1)}
                    >
                        ‹
                    </button>
                )}

                {/* Navigate to next image */}
                {activeImage < product.gallery.length - 1 && (
                    <button
                        className="
                            absolute right-2 top-1/2 -translate-y-1/2
                            bg-black/70 text-white px-3 py-2
                            hover:bg-black
                          "
                        onClick={() => setActiveImage((prev) => prev + 1)}
                    >
                        ›
                    </button>
                )}
            </div>

            <div className={"relative p-6 text-[clamp(8px,1.2vw,30px)]"}>
                {/* Product brand and name */}
                <h1 className="text-3xl font-semibold">{product.brand}</h1>
                <h2 className="text-2xl mb-6">{product.name}</h2>

                {/* Product attributes */}
                {product.attributes.map((attr) => {
                    const attrKebab = toKebabCase(attr.name);
                    const attrTestId = `product-attribute-${attrKebab}`;

                    return (
                        <div key={attr.external_id} className="mb-6" data-testid={attrTestId}>
                            <div className="font-bold mb-2">{attr.name}:</div>

                            <div className="flex gap-2">
                                {attr.items.map((item: AttributeItem) => {
                                    const isSelected =
                                        selectedAttributes[attr.external_id] === item.external_id;

                                    const itemTestId = `product-attribute-${attrKebab}-${item.displayValue}`;

                                    /* COLOR SWATCH */
                                    if (attr.type === "swatch") {
                                        return (
                                            <button
                                                key={item.external_id}
                                                data-testid={itemTestId}   // ✅ REQUIRED
                                                onClick={() =>
                                                    selectAttribute(attr.external_id, item.external_id)
                                                }
                                                className={`w-8 h-8 border ${
                                                    isSelected ? "border-gray-900" : "border-gray-300"
                                                }`}
                                                style={{ backgroundColor: item.value }}
                                            />
                                        );
                                    }

                                    /* TEXT ATTRIBUTE */
                                    return (
                                        <button
                                            key={item.external_id}
                                            data-testid={itemTestId}   // ✅ REQUIRED
                                            onClick={() =>
                                                selectAttribute(attr.external_id, item.external_id)
                                            }
                                            className={`px-4 py-2 border ${
                                                isSelected ? "bg-black text-white" : "bg-white"
                                            }`}
                                        >
                                            {item.value}
                                        </button>
                                    );
                                })}
                            </div>
                        </div>
                    );
                })}

                {/* Product price */}
                <div className="font-bold mt-6">PRICE:</div>
                <div className="font-semibold mb-6" data-testid="product-price">
                    {getFormattedPrice(product.prices)}
                </div>

                {/* Add to cart */}
                <button
                    data-testid="add-to-cart"
                    disabled={!allAttributesSelected || !product.inStock}
                    className={`w-full py-3 text-white font-semibold ${
                        allAttributesSelected && product.inStock
                            ? "bg-green-500"
                            : "bg-gray-400 cursor-not-allowed"
                    }`}
                    onClick={() => {
                        if (!allAttributesSelected || !product.inStock) return;
                        addToCart({ product, selectedAttributes, quantity: 1 });
                        openCart();
                    }}
                >
                    ADD TO CART
                </button>

                {/* Product description */}
                <div className="mt-6 reading-relaxed" data-testid="product-description">
                    {descriptionExpanded
                        ? parse(product.description)
                        : truncateText(product.description, 250)}

                    {isLong && (
                        <button
                            className="ml-2 text-green-600 font-medium"
                            onClick={() => setDescriptionExpanded((prev) => !prev)}
                        >
                            {descriptionExpanded ? "Read less" : "Read more"}
                        </button>
                    )}
                </div>
            </div>
        </div>
    );
}