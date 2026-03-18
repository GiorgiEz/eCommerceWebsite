import { useGraphQL } from "./useGraphQL";
import { GET_CATEGORIES } from "../graphql/queries/categories";
import type { Category } from "../utils/types.ts";


type Response = {
    categories: Category[];
};

export function useCategories() {
    return useGraphQL<Response>(GET_CATEGORIES);
}