import { gql } from "graphql-request";

/* GraphQL query to get all categories */
export const GET_CATEGORIES = gql`
{
  categories {
    name
  }
}
`;