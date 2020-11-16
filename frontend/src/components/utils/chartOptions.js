export default {
  legend: {
    display: false
  },
  scale: {
    angleLines: {
      display: false
    },
    ticks: {
      callback: function () {
        return ""
      },
      backdropColor: "rgba(0, 0, 0, 0)",
      suggestedMin: 0,
      suggestedMax: 10
    }
  }
}
