// 點擊編輯暱稱按鈕，顯示相對應區塊
document.querySelector('.board__user__btn').addEventListener('click', (e) => {
  if (e.target.classList.contains('board__user__btn__edit_nickname')) {
    document.querySelector('.board__nickname__form').classList.toggle('hidden')
  }  
})
