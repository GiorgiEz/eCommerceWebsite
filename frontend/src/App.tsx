import { BrowserRouter, Routes, Route } from "react-router-dom";
import Header from "./components/Header";
import ProductListPage from "./pages/ProductListPage";
import ProductDetailsPage from "./pages/ProductDetailsPage";
import { CategoryProvider } from "./context/CategoryContext";
import { CartProvider } from "./context/CartContext.tsx";

function App() {
    return (
        <BrowserRouter>
            <CategoryProvider>
                <CartProvider>
                    <Header />

                    <Routes>
                        <Route path="/" element={<ProductListPage />} />
                        <Route path="/product/:externalId" element={<ProductDetailsPage />} />
                    </Routes>
                </CartProvider>
            </CategoryProvider>
        </BrowserRouter>
    );
}

export default App;