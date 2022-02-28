// クッキーの値を取得する。
export function getCookieValue (searchKey) {
  if (typeof searchKey === 'undefined') { // typeofは変数の型を返却する。
    return ''
  }

  let val = ''

  // document.cookieでcookieにアクセスできる。
  // cookieはname=aaa;age=19;みたいな感じになっている。
  // splitは指定した文字ごとに区切られた配列を返す。
  // よって、document.cookie.split(';')とすると、その返り値は、
  // ['name=aaa','age=19']という配列になる。
  // それらをforEachで回して、さらに['name', 'aaa']という配列にしている。
  // const[key, value]は分割代入なので、key='name', value='aaa'となる。
  document.cookie.split(';').forEach(cookie => {
    const [key, value] = cookie.split('=')
    if (key === searchKey) {
      return val = value
    }
  })

  return val
}

// エラーコードの定義
export const OK = 200
export const CREATED = 201
export const NOT_FOUND = 404
export const UNAUTHORIZED = 419
export const UNPROCESSABLE_ENTITY = 422
export const INTERNAL_SERVER_ERROR = 500
export const UNAUTHORIZED_MESSAGE = 'セッション有効期限が切れました。再度ログインをお願いします。'
export const EMAIL_PATTERN = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]+.[A-Za-z0-9]+$/