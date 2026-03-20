import { GraphQLClient } from "graphql-request";

// Initialize GraphQL client for sending requests to the backend API endpoint
export const client = new GraphQLClient(
    "http://localhost/eCommerceWebsite/public/graphql"
);