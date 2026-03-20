import { BrowserRouter, Routes, Route } from "react-router-dom";
import Header from "./components/Header";
import ProductListPage from "./pages/ProductListPage";
import ProductDetailsPage from "./pages/ProductDetailsPage";
import { CategoryProvider } from "./context/CategoryContext";
import { CartProvider } from "./context/CartContext.tsx";


// Root application component that sets up routing and global state providers
function App() {
    return (
        <BrowserRouter>
            {/* Provides global category state */}
            <CategoryProvider>
                {/* Provides global cart state */}
                <CartProvider>

                    {/* Persistent header (navigation + cart) */}
                    <Header />

                    {/* Application routes */}
                    <Routes>
                        {/* Product listing page */}
                        <Route path="/" element={<ProductListPage />} />

                        {/* Product details page (dynamic route by externalId) */}
                        <Route path="/product/:externalId" element={<ProductDetailsPage />} />
                    </Routes>

                </CartProvider>
            </CategoryProvider>
        </BrowserRouter>
    );
}

export default App;