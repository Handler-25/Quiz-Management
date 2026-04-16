<html>
<head>
<script src="quiz.js"></script>
<body>
<div id="quiz-canvas" class="space-y-6 p-6 bg-gray-50 min-h-screen">
  
  <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border">
    <div>
      <h2 class="text-xl font-bold text-gray-800">Untitled Quiz Template</h2>
      <p class="text-sm text-gray-500">Last saved: Just now</p>
    </div>
    <div class="space-x-2">
      <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Preview</button>
      <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Save Template</button>
    </div>
  </div>

  <div id="question-list" class="space-y-4">
    <div class="bg-white p-6 rounded-xl border-l-8 border-indigo-500 shadow-sm relative group">
      <div class="flex justify-between mb-4">
        <span class="text-xs font-bold uppercase tracking-wider text-indigo-500">Question 1: Multiple Choice</span>
        <button class="text-gray-400 hover:text-red-500">Remove</button>
      </div>
      <input type="text" placeholder="Enter your question here..." class="w-full text-lg font-medium border-b border-gray-200 focus:border-indigo-500 outline-none pb-2 mb-4">
      
      <div class="grid grid-cols-2 gap-4">
        <div class="flex items-center space-x-2">
          <input type="radio" name="q1">
          <input type="text" placeholder="Option A" class="border p-2 rounded w-full text-sm">
        </div>
        <div class="flex items-center space-x-2">
          <input type="radio" name="q1">
          <input type="text" placeholder="Option B" class="border p-2 rounded w-full text-sm">
        </div>
      </div>
    </div>
  </div>

  <button onclick="addQuestion()" class="w-full py-4 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 hover:border-indigo-400 hover:text-indigo-500 transition-all font-medium">
    + Add New Question
  </button>
</div>

<script>
  function addQuestion() {
    const list = document.getElementById('question-list');
    const newCount = list.children.length + 1;
    const card = `
      <div class="bg-white p-6 rounded-xl border-l-8 border-gray-300 shadow-sm animate-fade-in">
        <div class="flex justify-between mb-4">
          <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Question ${newCount}</span>
        </div>
        <input type="text" placeholder="New Question..." class="w-full text-lg border-b border-gray-100 outline-none pb-2">
      </div>`;
    list.insertAdjacentHTML('beforeend', card);
  }
</script>
<body>
