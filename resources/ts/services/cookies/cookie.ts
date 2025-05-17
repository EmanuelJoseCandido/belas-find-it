import Cookies from "js-cookie";

const TOKEN_NAME = "belas_find_it_token";

export default {
  setToken(token: string) {
    Cookies.set(TOKEN_NAME, token, { expires: 1 });
  },

  getToken() {
    return Cookies.get(TOKEN_NAME);
  },

  deleteToken() {
    Cookies.remove(TOKEN_NAME);
  },
};
