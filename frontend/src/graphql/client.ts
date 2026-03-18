import { GraphQLClient } from "graphql-request";

export const client = new GraphQLClient(
    "http://localhost/eCommerceWebsite/public/graphql"
);