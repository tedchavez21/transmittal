@extends('layout.layout')

@section('title', 'Officer of the day')

@section('content')
    <h1>Officer of the Day</h1>
    <p>This page is for nl entry. You must enter your name before adding records.</p>

    @if(!$officerName)
        <form action="{{ route('officer.login') }}" method="POST" style="margin-top: 16px;">
            @csrf
            <label for="officerName">Officer of the day Name:</label>
            <select id="officerName" name="officerName" required>
                <option value="">Select Officer of the day</option>
                <option value="Gemmary Eiden Chavez">Gemmary Eiden Chavez</option>
                <option value="John Vincent Chico">John Vincent Chico</option>
                <option value="John Daryl Cruz">John Daryl Cruz</option>
                <option value="Shaila Jade Santos">Shaila Jade Santos</option>
                <option value="Lorena Jane Policarpio">Lorena Jane Policarpio</option>
                <option value="Bernadette Santiago">Bernadette Santiago</option>
                <option value="Carol Lumibao">Carol Lumibao</option>
                <option value="Romellyn Pornuevo">Romellyn Pornuevo</option>
                <option value="Glen Bondoc">Glen Bondoc</option>
                <option value="John Patrick Aceron">John Patrick Aceron</option>
                <option value="Ted Eiden Chavez">Ted Eiden Chavez</option>
                <option value="Uzziel Martinez">Uzziel Martinez</option>
                <option value="Hanna Marie Lorica">Hanna Marie Lorica</option>
                <option value="Jessica Rose Flores">Jessica Rose Flores</option>
                <option value="Jenica Atchico">Jenica Atchico</option>
                <option value="Julie Ann Espejo">Julie Ann Espejo</option>
                <option value="Ian Marvic Lumibao">Ian Marvic Lumibao</option>
                <option value="Nelson Alvaro">Nelson Alvaro</option>
                <option value="Jia Joanna Paler">Jia Joanna Paler</option>
                <option value="Jammie Padilla">Jammie Padilla</option>
                <option value="Nicole Ann Carlos">Nicole Ann Carlos</option>
                <option value="Myleen Concepcion">Myleen Concepcion</option>
                <option value="Raven Guingon">Raven Guingon</option>
                <option value="Melody Returban">Melody Returban</option>
                <option value="Clarissa Centeno">Clarissa Centeno</option>
            </select>
            <button type="submit">Enter</button>
        </form>
    @else
        <p>Officer: <strong>{{ $officerName }}</strong></p>
        <form action="{{ route('officer.logout') }}" method="POST" style="display: inline-block; margin-bottom: 16px;">
            @csrf
            <button type="submit">Logout</button>
        </form>
        @if($officerApproved)
            <p style="color: green;">Your login is approved. You may add records.</p>
            <button class="addRecordButton">Add Record</button>
        @else
            <p style="color: orange;">Your login is pending admin approval. You cannot add records until it is approved.</p>
        @endif
        <dialog class="addRecordDialog">
            <form action="{{ route('records') }}" method="POST">
                @csrf
                <h3>Add Record</h3>
            <label for="farmerName">Farmer Name:</label>
            <input type="text" id="farmerName" name="farmerName">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address">
            <label for="line">Line:</label>
            <select name="line" id="line">
                <option value="">Select Line</option>
                <option value="rice">rice</option>
                <option value="corn">corn</option>
                <option value="high-value">High-Value Crops</option>
                <option value="clti">CLTI</option>
                <option value="livestock">Livestock</option>
                <option value="non-crop">Non-Crop</option>
                <option value="fisheries">Fisheries</option>
            </select>
            <label for="program">Program:</label>
            <select name="program" id="program">
                <option value="">Select Program</option>
                <option value="program1">RSBSA</option>
                <option value="program2">LBP</option>
                <option value="program3">Program 3</option>
            </select>
            <label for="causeOfDamage">Cause of Damage:</label>
            <input type="text" id="causeOfDamage" name="causeOfDamage">
            <label for="modeOfPayment">Mode of payment:</label>
            <select name="modeOfPayment" id="modeOfPayment">
                <option value="">Select Mode of payment</option>
                <option value="check">Check</option>
                <option value="palawan">Palawan Pay</option>
            </select>
            <label for="remarks">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks">
            <button type="submit">Add Record</button>
        </form>
        <button class="closeAddRecordModal">Close</button>
    </dialog>
    <x-table :records="$records" :showDelete="false" :showCheckbox="false" />
    <dialog class="editRecordDialog">
        <form class="editRecordform" method="POST">
            @csrf
            @method('PUT')
            <label for="farmerName">Farmer Name:</label>
            <input type="text" id="farmerName" name="farmerName">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address">
            <label for="line">Line:</label>
            <select name="line" id="line">
                <option value="">Select Line</option>
                <option value="rice">rice</option>
                <option value="corn">corn</option>
                <option value="high-value">High-Value Crops</option>
                <option value="clti">CLTI</option>
                <option value="livestock">Livestock</option>
                <option value="non-crop">Non-Crop</option>
                <option value="fisheries">Fisheries</option>
            </select>
            <label for="program">Program:</label>
            <select name="program" id="program">
                <option value="">Select Program</option>
                <option value="program1">RSBSA</option>
                <option value="program2">LBP</option>
                <option value="program3">Program 3</option>
            </select>
            <label for="causeOfDamage">Cause of Damage:</label>
            <input type="text" id="causeOfDamage" name="causeOfDamage">
            <label for="modeOfPayment">Mode of payment:</label>
            <select name="modeOfPayment" id="modeOfPayment">
                <option value="">Select Mode of payment</option>
                <option value="check">Check</option>
                <option value="palawan">Palawan Pay</option>
            </select>
            <label for="remarks">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks">
            <button type="submit">Update Record</button>
        </form>
        <button class="closeEditRecordDialog">Close</button>
    </dialog>
    @endif
@endsection