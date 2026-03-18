import { useGraphQL } from "./useGraphQL";
import { GET_PRODUCTS } from "../graphql/queries/products";
import type { Product } from "../utils/types.ts";

type Response = {
    products: Product[];
};

export function useProducts(category?: string) {
    return useGraphQL<Response>(GET_PRODUCTS, { category });
}