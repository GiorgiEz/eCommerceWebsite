import { gql } from "graphql-request";

/* GraphQL mutation to create an order */
export const CREATE_ORDER = gql`
mutation CreateOrder($input: CreateOrderInput!) {
  createOrder(input: $input)
}
`;