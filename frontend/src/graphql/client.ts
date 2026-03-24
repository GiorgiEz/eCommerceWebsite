import { GraphQLClient } from "graphql-request";

const is_dev = true;

const endpoint =
    is_dev
        ? "http://localhost/eCommerceWebsite/public/graphql"
        : window.location.origin + "/graphql";

export const client = new GraphQLClient(endpoint);