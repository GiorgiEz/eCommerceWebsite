export const GET_PRODUCTS = `
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