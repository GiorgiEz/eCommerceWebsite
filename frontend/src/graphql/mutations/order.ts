import { gql } from "graphql-request";

export const CREATE_ORDER = gql`
mutation CreateOrder($input: CreateOrderInput!) {
  createOrder(input: $input)
}
`;