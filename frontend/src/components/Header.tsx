import { useCategories } from "../hooks/useCategories";
import { useCategory } from "../context/CategoryContext";
import { useLocation, useNavigate } from "react-router-dom";
import type { Category } from "../utils/types.ts";
import CartOverlay from "./CartOverlay";


/*
    Header component fixed at the top; displays category navigation or a
    back button based on current route, and includes the cart overlay
*/
export default function Header() {
    const { data } = useCategories();
    const { category, setCategory } = useCategory();

    // React Router hooks for reading current path and navigating programmatically
    const location = useLocation();
    const navigate = useNavigate();

    const isHomePage = location.pathname === "/";
    const categories: Category[] = (data?.categories ?? []);

    // Renders a category button and highlights it if it is the currently selected category
    const renderCategoryButton = (name: string) => {
        const isActive = category === name;

        return (
            <button
                key={name}
                onClick={() => setCategory(name)}
                data-testid={isActive ? "active-category-link" : "category-link"}
                className={`uppercase text-sm font-medium pb-1 border-b-2 h-[4vh] text-[clamp(10px,1.5vw,20px)] ${
                    isActive
                        ? "border-green-500 text-black"
                        : "border-transparent text-gray-500 hover:text-black"
                }`}
            >
                {name}
            </button>
        );
    };

    return (
        <header className="fixed top-0 left-0 w-full z-50 border-b border-gray-200 bg-white">
            <div className="max-w-7xl mx-auto flex items-center justify-between h-[8vh] px-6">

                {/* LEFT SIDE: Category navigation or back button depending on route */}
                <nav className="flex items-center gap-6">
                    {isHomePage ? (
                        <>
                            {categories.map((c) => renderCategoryButton(c.name))}
                        </>
                    ) : (
                        <button
                            onClick={() => navigate("/")}
                            className="uppercase font-medium cursor-pointer hover:scale-110 h-[4vh] text-[clamp(20px,1.2vw,30px)]"
                        >
                            BACK
                        </button>
                    )}
                </nav>

                {/* CENTER: Logo positioned absolutely in the middle */}
                <div className="absolute left-1/2 transform -translate-x-1/2">
                    <div className="font-bold text-[clamp(20px,3vw,40px)]">🛍️</div>
                </div>

                {/* RIGHT: Cart overlay component */}
                <CartOverlay />

            </div>
        </header>
    );
}