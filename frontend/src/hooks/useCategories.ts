import { useGraphQL } from "./useGraphQL";
import { GET_CATEGORIES } from "../graphql/queries/categories";
import type { Category } from "../utils/types.ts";


export function useCategories() {
    return useGraphQL<{categories: Category[]}>(GET_CATEGORIES);
}