const verticalLinePlugin = {
  getLinePosition: function (chart, pointIndex) {
    const meta = chart.getDatasetMeta(0);
    const data = meta.data;
    return data[pointIndex]._model.x;
  },
  renderVerticalLine: function (chartInstance, pointIndex) {
    const lineLeftOffset = this.getLinePosition(chartInstance, pointIndex);
    const scale = chartInstance.scales['y-axis-0'];
    const context = chartInstance.chart.ctx;
    context.beginPath();
    context.strokeStyle = '#C9D1D9';
    context.lineWidth = 1;
    context.moveTo(lineLeftOffset, scale.top);
    context.lineTo(lineLeftOffset, scale.bottom);
    context.stroke();
  },

  afterDatasetsDraw: function (chart, easing) {
    if (chart.config.lineAtIndex) {
      chart.config.lineAtIndex.forEach(pointIndex => this.renderVerticalLine(chart, pointIndex));
    }
  }
};
Chart.plugins.register(verticalLinePlugin);