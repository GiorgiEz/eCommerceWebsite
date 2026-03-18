import { createContext, type ReactNode, useContext, useState } from "react";

type CategoryContextType = {
    category: string;
    setCategory: (category: string) => void;
};

const CategoryContext = createContext<CategoryContextType | undefined>(undefined);

export function CategoryProvider({ children }: { children: ReactNode }) {
    const [category, setCategory] = useState<string>("all");

    return (
        <CategoryContext.Provider value={{ category, setCategory }}>
            {children}
        </CategoryContext.Provider>
    );
}

export function useCategory() {
    const context = useContext(CategoryContext);

    if (!context) {
        throw new Error("useCategory must be used inside CategoryProvider");
    }

    return context;
}