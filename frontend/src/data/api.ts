const api = "http://localhost:3000/api/query";
const username = import.meta.env.VITE_API_USERNAME;
const password = import.meta.env.VITE_API_PASSWORD;

export interface Book {
  title: string;
  slug: string;
  date: string;
  files: {
    type: string;
    url: string;
    description: string;
  }[];
}

export interface ApiResponse {
  code: number;
  result: Book[];
}

const fetchQuery = async (body: {}) => {
  const authString = `${username}:${password}`;
  const encodedAuthString = Buffer.from(authString).toString("base64");
  try {
    const response = await fetch(api, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        Authorization: `Basic ${encodedAuthString}`,
      },
      body: JSON.stringify(body),
    });
    return (await response.json()) as Promise<ApiResponse>;
  } catch (error) {
    console.error(error);
  }
};

export async function fetchBooks() {
  const query = "page('books').children";
  const select = {
    title: true,
    slug: true,
    date: "page.date.toDate('d.m.Y')",
    files: {
      query: "page.files",
      select: {
        type: true,
        url: true,
        description: true,
      },
    },
  };
  try {
    const response = await fetchQuery({ query, select });
    if (response && response.code === 200) {
      return response.result;
    }
  } catch (error) {
    console.error(error);
  }
}
