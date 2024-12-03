export function getFormattedDate(date, shiftInfos) {
    const shift = shiftInfos.find(info => info.date === date);
    return shift ? shift.date : null;
}

export function getEndOfDayAfterThreeMonth(){
    const now = new Date();
    const endOfDate = new Date(now.getFullYear(), now.getMonth()+3, 0);
    return endOfDate;
}

// shiftUtils.js
export function getShiftClass(hour, shift) {
    const clockIn = parseInt(shift.clockIn.substring(0, 2));
    let clockOut = parseInt(shift.clockOut.substring(0, 2));
  
    if (clockOut === 0) {
      clockOut = 24;
    }
  
    const clockInMinutes = parseInt(shift.clockIn.substring(3, 5));
    const clockOutMinutes = parseInt(shift.clockOut.substring(3, 5));
  
    // 開始時間が該当する時間帯かチェック
    if (hour.hour === clockIn) {
      if (clockInMinutes === 15) {
        return 'background: linear-gradient(90deg, transparent 0%, transparent 25%, hsla(200, 100%, 85%, .5) 25%, hsla(200, 100%, 85%, .5) 100%)';
      } else if (clockInMinutes === 30) {
        return 'background: linear-gradient(90deg, transparent 0%, transparent 50%, hsla(200, 100%, 85%, .5) 50%, hsla(200, 100%, 85%, .5) 100%)';
      } else if (clockInMinutes === 45) {
        return 'background: linear-gradient(90deg, transparent 0%, transparent 75%, hsla(200, 100%, 85%, .5) 75%, hsla(200, 100%, 85%, .5) 100%)';
      } else {
        return 'background-color: hsla(200, 100%, 85%, .5);';
      }
    }
  
    // 終了時間が該当する時間帯かチェック
    if (hour.hour === clockOut) {
      if (clockOutMinutes === 15) {
        return 'background: linear-gradient(90deg, hsla(200, 100%, 85%, .5) 0%, hsla(200, 100%, 85%, .5) 25%, transparent 25%, transparent 100%)';
      } else if (clockOutMinutes === 30) {
        return 'background: linear-gradient(90deg, hsla(200, 100%, 85%, .5) 0%, hsla(200, 100%, 85%, .5) 50%, transparent 50%, transparent 100%)';
      } else if (clockOutMinutes === 45) {
        return 'background: linear-gradient(90deg, hsla(200, 100%, 85%, .5) 0%, hsla(200, 100%, 85%, .5) 75%, transparent 75%, transparent 100%)';
      } else {
        return 'background-color: hsla(200, 100%, 85%, .5);';
      }
    }
  
    // それ以外の時間帯の全体色
    if (hour.hour > clockIn && hour.hour < clockOut) {
      return 'background-color: hsla(200, 100%, 85%, .5);';
    }
  
    return '';
}
  