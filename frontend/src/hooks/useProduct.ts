import { useGraphQL } from "./useGraphQL";
import { GET_PRODUCT } from "../graphql/queries/product";
import type { Product } from "../utils/types.ts";


type Response = {
    product: Product;
};

export function useProduct(externalId: string) {
    return useGraphQL<Response>(GET_PRODUCT, {
        externalId
    });
}