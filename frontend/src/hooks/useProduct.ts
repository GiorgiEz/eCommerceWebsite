import { useMemo } from "react";
import { useGraphQL } from "./useGraphQL";
import { GET_PRODUCT } from "../graphql/queries/product";
import type { Product } from "../utils/types.ts";


/* Custom hook to fetch a single product by externalId using GraphQL with memoized variables */
export function useProduct(externalId: string) {
    const variables = useMemo(() => ({ externalId }), [externalId]);
    return useGraphQL<{product: Product}>(GET_PRODUCT, variables);
}