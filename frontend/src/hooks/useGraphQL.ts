import { useEffect, useState } from "react";
import { client } from "../graphql/client";

export function useGraphQL<T>(query: string, variables?: Record<string, any>) {
    const [data, setData] = useState<T | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        async function fetchData() {
            try {
                const result = await client.request<T>(query, variables);
                setData(result);
            } catch (err) {
                setError("GraphQL request failed");
                console.error(err);
            } finally {
                setLoading(false);
            }
        }

        fetchData();
    }, [query, variables]);

    return { data, loading, error };
}