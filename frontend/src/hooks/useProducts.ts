import { useMemo } from "react";
import { useGraphQL } from "./useGraphQL";
import { GET_PRODUCTS } from "../graphql/queries/products";
import type { Product } from "../utils/types";


export function useProducts(category?: string) {
    const variables = useMemo(() => ({ category }), [category]);
    return useGraphQL<{products: Product[]}>(GET_PRODUCTS, variables);
}