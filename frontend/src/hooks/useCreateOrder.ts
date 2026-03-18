import {client} from "../graphql/client";
import {CREATE_ORDER} from "../graphql/mutations/order";
import type { CreateOrderInput } from "../utils/types";

export function useCreateOrder() {
    const createOrder = async (input: CreateOrderInput) => {
        try {
            return await client.request(CREATE_ORDER, {input});
        } catch (error) {
            console.error("Create order failed:", error);
            throw error;
        }
    };

    return { createOrder };
}