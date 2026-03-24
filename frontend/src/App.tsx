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
            <CategoryProvider>
                <CartProvider>

                    <Header />

                    <Routes>
                        {/* Home = default category */}
                        <Route path="/" element={<ProductListPage />} />

                        {/* ✅ Category routes */}
                        <Route path="/:category" element={<ProductListPage />} />

                        {/* Product page */}
                        <Route path="/product/:externalId" element={<ProductDetailsPage />} />
                    </Routes>

                </CartProvider>
            </CategoryProvider>
        </BrowserRouter>
    );
}

export default App;