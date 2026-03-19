import {useState} from "react";
import {client} from "../graphql/client";
import {CREATE_ORDER} from "../graphql/mutations/order";
import type {CreateOrderInput} from "../utils/types";

export function useCreateOrder() {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const createOrder = async (input: CreateOrderInput) => {
        if (loading) return; // prevent duplicate calls

        try {
            setLoading(true);
            setError(null);

            return await client.request(CREATE_ORDER, {input});

        } catch (err) {
            console.error("Create order failed:", err);
            setError("Order failed");
            throw err;

        } finally {
            setLoading(false);
        }
    };

    return { createOrder, loading, error };
}