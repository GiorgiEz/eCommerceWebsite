import { gql } from "graphql-request";

export const GET_PRODUCTS = gql`
query ProductsByCategory($category: String) {
  products(category: $category) {
    external_id
    name
    inStock
    thumbnail
    prices {
      amount
      currency {
        symbol
        label
      }
    }
  }
}
`;