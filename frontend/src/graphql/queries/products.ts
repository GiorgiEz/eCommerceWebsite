import { gql } from "graphql-request";

/* GraphQL query to fetch products by category with basic details and pricing */
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