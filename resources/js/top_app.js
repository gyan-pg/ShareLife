// jquery読み込み
window.$ = window.jQuery = require('jquery')

$(window).on('load', function() {
  // heroのメッセージがふわっとするやつ
  const $top_message = $('.js-top__message')
  const $top_message_title = $('.js-top__message--title')
  // 読み込み時のみ実行
  setTimeout(function () {
    $top_message.addClass('active')
    setTimeout(function () {
      $top_message_title.addClass('active')
    },1000)
  }, 500)
})

$(function () {
  const $window = $(window)
  const $nav = $('.js-nav')
  const $nav_item = $('.js-nav-item')
  const nav_height = $('.js-nav').height()

  // menuの制御
  const $hamberger = $('.js-hamburger')
  const $navMenu = $('.js-nav-menu')
  $hamberger.on('click', function () {
    $(this).toggleClass('active')
    $navMenu.toggleClass('active')
  })

  // aboutのDOM
  const $about1 = $('.js-about-1')
  const $about2 = $('.js-about-2')
  const $about3 = $('.js-about-3')

  // 使い方のDOM
  const $usageLeft = $('.js-usage-top-left')
  const $usageRight = $('.js-usage-top-right')
  
  // aboutを表示するスクロールの感覚
  const showSpan = $about1.outerHeight(true)

  // heroバナー部の高さ
  let windowHeight = $window.height()

  // aboutの高さ
  let aboutHeight = $('#about').outerHeight()

  // スクロールきっかけでスタイルが変化するやつ
  $window.on('scroll' ,function () {
    let scroll = $(this).scrollTop()
    // スクロールがheroを超えるちょっと前
    if (scroll > windowHeight - nav_height) {
      $nav_item.addClass('active')
      $nav.addClass('active')
    } else {
      $nav_item.removeClass('active')
      $nav.removeClass('active')
    }
    // スクロールがheroを超えた後 1つ目のaboutの表示
    if (scroll > windowHeight) {
      $about1.addClass('active')
    } else {
      $about1.removeClass('active')
    }
    // 2つ目のaboutの表示
    if (scroll > windowHeight + showSpan) {
      $about2.addClass('active')
    } else {
      $about2.removeClass('active')
    }
    // 3つ目のabout
    if (scroll > windowHeight + showSpan * 2) {
      $about3.addClass('active')
    } else {
      $about3.removeClass('active')
    }
    // 使い方のところの表示
    if (scroll > windowHeight + aboutHeight - windowHeight * 1 / 2) {
      $usageLeft.addClass('active')
      $usageRight.addClass('active')
    } else {
      $usageLeft.removeClass('active')
      $usageRight.removeClass('active')
    }
  })

  // チーム結成後の画面の説明
  const $slide1 = $('.js-slide1')
  const $slide2 = $('.js-slide2')
  const $slide3 = $('.js-slide3')

  // スライドの切り替え
  // 制御用のボタン
  const $showBtn1 = $('.js-show-slide1')
  const $showBtn2 = $('.js-show-slide2')
  const $showBtn3 = $('.js-show-slide3')

  $showBtn1.addClass('active')
  $slide2.hide()
  $slide3.hide()

  const duration = 300
  // スライドの切り替え
  $showBtn1.on('click', function(){
    $(this).addClass('active')
    $showBtn2.removeClass('active')
    $showBtn3.removeClass('active')
    setTimeout(() => {
      $slide1.fadeIn(duration)
    }, duration + 50)
    $slide2.fadeOut(duration)
    $slide3.fadeOut(duration)
  })

  $showBtn2.on('click', function(){
    $(this).addClass('active')
    $showBtn1.removeClass('active')
    $showBtn3.removeClass('active')
    setTimeout(() => {
      $slide2.fadeIn(duration)
    }, duration + 50)
    $slide3.fadeOut(duration)
    $slide1.fadeOut(duration)
  })

  $showBtn3.on('click', function(){
    $(this).addClass('active')
    $showBtn1.removeClass('active')
    $showBtn2.removeClass('active')
    setTimeout(() => {
      $slide3.fadeIn(duration)
    }, duration + 50)
    $slide1.fadeOut(duration)
    $slide2.fadeOut(duration)
  })

})