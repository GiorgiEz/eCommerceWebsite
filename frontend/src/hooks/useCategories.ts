import { useGraphQL } from "./useGraphQL";
import { GET_CATEGORIES } from "../graphql/queries/categories";
import type { Category } from "../utils/types.ts";


/* Custom hook to fetch categories using GraphQL */
export function useCategories() {
    return useGraphQL<{categories: Category[]}>(GET_CATEGORIES);
}