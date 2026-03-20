import { gql } from "graphql-request";

/* GraphQL query to get all information about one specific product */
export const GET_PRODUCT = gql`
query ProductById($externalId: String!) {
  product(external_id: $externalId) {
    external_id
    name
    brand
    description
    gallery
    inStock

    attributes {
      external_id
      name
      type
      items {
        external_id
        value
        displayValue
      }
    }

    prices {
      amount
      currency {
        symbol
      }
    }
  }
}
`;