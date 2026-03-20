import { createContext, type ReactNode, useContext, useState } from "react";
import type { CategoryContextType} from "../utils/types";


// Context to store and share the selected category state across components
const CategoryContext = createContext<CategoryContextType | undefined>(undefined);

// Provider component that manages category state and makes it available to the app
export function CategoryProvider({ children }: { children: ReactNode }) {
    const [category, setCategory] = useState<string>("all");

    return (
        <CategoryContext.Provider value={{ category, setCategory }}>
            {children}
        </CategoryContext.Provider>
    );
}

// Custom hook to access category state safely within components
export function useCategory() {
    const context = useContext(CategoryContext);

    if (!context) {
        throw new Error("useCategory must be used inside CategoryProvider");
    }

    return context;
}